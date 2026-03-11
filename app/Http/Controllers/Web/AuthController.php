<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Wallet;
use App\Models\LoginLog;
use App\Models\DeviceLog;
use App\Models\OneTimeCode;
use App\Services\OtpService;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => ['required'],
            'password' => ['required'],
            'pin' => ['required', 'digits:4'],
        ]);

        $user = User::where('email', $request->identifier)
            ->orWhere('mobile_phone', $request->identifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password) || !Hash::check($request->pin, (string) $user->security_pin)) {
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
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'login_failed',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => ['reason' => 'invalid_credentials'],
                ]);
            }
            return back()->withErrors([
                'identifier' => 'The provided credentials do not match our records.',
            ])->onlyInput('identifier');
        }

        if ($user->account_locked_until && now()->lessThan($user->account_locked_until)) {
            return back()->withErrors([
                'identifier' => 'Account temporarily locked.',
            ])->onlyInput('identifier');
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
            $purpose = 'device_verification';
            $method = 'sms';
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
            session([
                'pending_challenge_user_id' => $user->id,
                'pending_challenge_purpose' => $purpose,
                'pending_device_fp' => $fingerprint,
            ]);

            if ($method === 'sms') {
                $otp = app(OtpService::class);
                if ($otp->canResend($user, $purpose)) {
                    $otp->issueSms($user, $purpose);
                }
            }

            return redirect()->route('verification.form');
        }

        Auth::login($user);
        $request->session()->regenerate();

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

        return redirect()->intended('dashboard');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_phone' => 'required|string|max:20|unique:users',
            'country_code' => 'required|string|size:2',
            'password' => 'required|string|min:8|confirmed',
            'security_pin' => 'required|digits:4',
        ]);

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

        $fingerprint = $request->header('X-Device-Id') ?: sha1(($request->userAgent() ?? '') . '|' . $request->ip());
        DeviceLog::firstOrCreate(
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

        session([
            'pending_challenge_user_id' => $user->id,
            'pending_challenge_purpose' => 'mobile_verify',
            'pending_device_fp' => $fingerprint,
        ]);

        $otp = app(OtpService::class);
        $otp->issueSms($user, 'mobile_verify');

        return redirect()->route('verification.form');
    }

    public function showVerificationForm(Request $request)
    {
        $userId = session('pending_challenge_user_id');
        $purpose = session('pending_challenge_purpose');

        if (!$userId || !$purpose) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $method = $purpose === 'login_totp' ? 'totp' : 'sms';

        return view('auth.verify-otp', [
            'method' => $method,
            'purpose' => $purpose,
            'maskedPhone' => $this->maskPhone($user->mobile_phone),
        ]);
    }

    public function verifyChallenge(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $userId = session('pending_challenge_user_id');
        $purpose = session('pending_challenge_purpose');
        $fingerprint = session('pending_device_fp');

        if (!$userId || !$purpose) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $ok = false;
        if ($purpose === 'login_totp') {
            if (!$user->two_factor_enabled || $user->two_factor_type !== 'totp' || !$user->two_factor_secret) {
                return redirect()->route('login');
            }
            $secret = decrypt($user->two_factor_secret);
            $ok = Totp::verify($secret, $request->code);
        } else {
            $ok = app(OtpService::class)->verify($user, $purpose, $request->code);
        }

        if (!$ok) {
            return back()->withErrors(['code' => 'Código inválido o expirado.']);
        }

        if ($purpose === 'mobile_verify' && !$user->mobile_verified_at) {
            $user->mobile_verified_at = now();
        }

        $user->failed_login_attempts = 0;
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        if ($purpose === 'device_verification' && $fingerprint) {
            DeviceLog::where('user_id', $user->id)
                ->where('device_fingerprint', $fingerprint)
                ->update(['is_trusted' => true, 'last_active_at' => now(), 'ip_address' => $request->ip()]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'created_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'challenge_verified',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'purpose' => $purpose,
                'device_fingerprint' => $fingerprint,
            ],
        ]);

        session()->forget(['pending_challenge_user_id', 'pending_challenge_purpose', 'pending_device_fp']);

        return redirect()->intended('dashboard');
    }

    public function resendChallenge(Request $request)
    {
        $userId = session('pending_challenge_user_id');
        $purpose = session('pending_challenge_purpose');

        if (!$userId || !$purpose) {
            return redirect()->route('login');
        }

        if ($purpose === 'login_totp') {
            return redirect()->route('verification.form');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $otp = app(OtpService::class);
        if ($otp->canResend($user, $purpose)) {
            $otp->issueSms($user, $purpose);
        }

        return redirect()->route('verification.form');
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function verifyPin(Request $request)
    {
        $request->validate(['pin' => 'required|digits:4']);
        if (!Hash::check($request->pin, $request->user()->security_pin)) {
            return response()->json(['message' => 'Invalid PIN'], 403);
        }
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'pin_verified',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return response()->json(['message' => 'PIN verified']);
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

    private function maskPhone(?string $phone): ?string
    {
        $phone = trim((string) $phone);
        if ($phone === '') {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if (strlen($digits) <= 4) {
            return $phone;
        }
        $last = substr($digits, -4);
        return '***' . $last;
    }
}
