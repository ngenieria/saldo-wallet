<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Wallet;
use App\Models\LoginLog;
use App\Models\DeviceLog;
use App\Models\OneTimeCode;
use App\Services\OtpService;
use App\Support\Jwt;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_phone' => 'required|string|max:20|unique:users',
            'country_code' => 'required|string|size:2',
            'password' => 'required|string|min:8|confirmed',
            'security_pin' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_phone' => $request->mobile_phone,
            'country_code' => $request->country_code,
            'password' => Hash::make($request->password),
            'security_pin' => Hash::make($request->security_pin),
            'kyc_status' => 'pending',
        ]);

        // Create default wallets
        $defaultCurrencies = ['USD', 'COP', 'EUR'];
        foreach ($defaultCurrencies as $currency) {
            Wallet::create([
                'user_id' => $user->id,
                'currency' => $currency,
                'balance' => 0,
            ]);
        }

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        app(OtpService::class)->issueSms($user, 'mobile_verify');

        return response()->json([
            'message' => 'Verification required',
            'requires_verification' => true,
            'purpose' => 'mobile_verify',
            'method' => 'sms',
        ], 202);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'password' => 'required|string',
            'pin' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('mobile_phone', $request->identifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password) || !Hash::check($request->pin, $user->security_pin)) {
            if ($user) {
                $user->increment('failed_login_attempts');
                if ($user->failed_login_attempts >= 5) {
                    $user->account_locked_until = now()->addMinutes(15);
                    $user->save();
                }
                LoginLog::create([
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed',
                    'created_at' => now(),
                ]);
            }
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        if ($user->account_locked_until && now()->lessThan($user->account_locked_until)) {
            return response()->json(['message' => 'Account temporarily locked'], 423);
        }

        $fingerprint = $request->header('X-Device-Id') ?: sha1(($request->userAgent() ?? '') . '|' . $request->ip());
        $device = DeviceLog::firstOrCreate(
            ['device_fingerprint' => $fingerprint, 'user_id' => $user->id],
            [
                'device_name' => $request->header('X-Device-Name'),
                'device_type' => $this->deviceTypeFromUserAgent($request->userAgent()),
                'os' => $request->header('X-Device-OS'),
                'browser' => $request->header('X-Device-Browser'),
                'ip_address' => $request->ip(),
                'location' => null,
                'is_trusted' => false,
            ]
        );
        $isNewDevice = !$device->is_trusted;
        $device->last_active_at = now();
        $device->ip_address = $request->ip();
        $device->save();

        $purpose = null;
        $method = null;
        if (!$user->mobile_verified_at) {
            $purpose = 'mobile_verify';
            $method = 'sms';
        } elseif ($isNewDevice) {
            if ($user->two_factor_enabled && $user->two_factor_type === 'totp') {
                $purpose = 'device_verification_totp';
                $method = 'totp';
            } else {
                $purpose = 'device_verification';
                $method = 'sms';
            }
        } elseif ($user->two_factor_enabled) {
            if ($user->two_factor_type === 'totp') {
                $purpose = 'login_totp';
                $method = 'totp';
            } else {
                $purpose = 'login_2fa';
                $method = 'sms';
            }
        }

        if ($purpose) {
            if ($method === 'sms') {
                $otp = app(OtpService::class);
                if ($otp->canResend($user, $purpose)) {
                    $otp->issueSms($user, $purpose);
                }
            }
            return response()->json([
                'message' => 'Verification required',
                'requires_verification' => true,
                'purpose' => $purpose,
                'method' => $method,
            ], 202);
        }

        $user->failed_login_attempts = 0;
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'created_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['device_fingerprint' => $fingerprint],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $jwt = Jwt::encode([
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 3600,
        ], config('app.key'));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'jwt' => $jwt,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function verifyPin(Request $request)
    {
        $request->validate(['pin' => 'required|digits:4']);

        if (!Hash::check($request->pin, $request->user()->security_pin)) {
            return response()->json(['message' => 'Invalid PIN'], 403);
        }

        return response()->json(['message' => 'PIN verified']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
            'pin' => 'required|digits:4',
            'code' => 'required|digits:6',
            'purpose' => 'required|string',
        ]);

        $user = User::where('email', $request->identifier)
            ->orWhere('mobile_phone', $request->identifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password) || !Hash::check($request->pin, $user->security_pin)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $purpose = (string) $request->purpose;
        $ok = false;
        if (in_array($purpose, ['login_totp', 'device_verification_totp'], true)) {
            if (!$user->two_factor_enabled || $user->two_factor_type !== 'totp' || !$user->two_factor_secret) {
                return response()->json(['message' => 'TOTP not enabled'], 400);
            }
            $secret = decrypt($user->two_factor_secret);
            $ok = Totp::verify($secret, (string) $request->code);
        } else {
            $ok = app(OtpService::class)->verify($user, $purpose, (string) $request->code);
        }

        if (!$ok) {
            return response()->json(['message' => 'Invalid or expired code'], 422);
        }

        if ($purpose === 'mobile_verify' && !$user->mobile_verified_at) {
            $user->mobile_verified_at = now();
        }

        $fingerprint = $request->header('X-Device-Id') ?: sha1(($request->userAgent() ?? '') . '|' . $request->ip());
        if (in_array($purpose, ['device_verification', 'device_verification_totp'], true)) {
            DeviceLog::where('user_id', $user->id)
                ->where('device_fingerprint', $fingerprint)
                ->update(['is_trusted' => true, 'last_active_at' => now(), 'ip_address' => $request->ip()]);
        }

        $user->failed_login_attempts = 0;
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        $jwt = Jwt::encode([
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 3600,
        ], config('app.key'));

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'challenge_verified',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['purpose' => $purpose, 'device_fingerprint' => $fingerprint],
        ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'jwt' => $jwt,
            'user' => $user,
        ]);
    }

    private function deviceTypeFromUserAgent(?string $ua): ?string
    {
        $ua = strtolower((string) $ua);
        if ($ua === '') {
            return null;
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) {
            return 'mobile';
        }
        return 'desktop';
    }
}
