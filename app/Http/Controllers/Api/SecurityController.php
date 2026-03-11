<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use App\Support\Totp;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function enableSms(Request $request)
    {
        $user = $request->user();

        if (!$user->mobile_verified_at) {
            return response()->json(['message' => 'Mobile not verified'], 422);
        }

        $user->two_factor_type = 'sms';
        $user->two_factor_enabled = false;
        $user->save();

        app(OtpService::class)->issueSms($user, 'enable_2fa_sms');

        return response()->json([
            'message' => 'Verification required',
            'requires_verification' => true,
            'purpose' => 'enable_2fa_sms',
            'method' => 'sms',
        ], 202);
    }

    public function verifySms(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $user = $request->user();
        $ok = app(OtpService::class)->verify($user, 'enable_2fa_sms', (string) $request->code);

        if (!$ok) {
            return response()->json(['message' => 'Invalid or expired code'], 422);
        }

        $user->two_factor_enabled = true;
        $user->two_factor_type = 'sms';
        $user->save();

        return response()->json(['message' => '2FA enabled']);
    }

    public function enableTotp(Request $request)
    {
        $user = $request->user();
        $secret = Totp::generateSecret();
        $user->two_factor_type = 'totp';
        $user->two_factor_secret = encrypt($secret);
        $user->two_factor_enabled = false;
        $user->save();
        return response()->json([
            'secret' => $secret,
        ]);
    }

    public function verifyTotp(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $user = $request->user();
        if (!$user->two_factor_secret) {
            return response()->json(['message' => 'No TOTP secret'], 400);
        }
        $secret = decrypt($user->two_factor_secret);
        if (\App\Support\Totp::verify($secret, $request->code)) {
            $user->two_factor_enabled = true;
            $user->save();
            return response()->json(['message' => '2FA enabled']);
        }
        return response()->json(['message' => 'Invalid code'], 422);
    }

    public function disable2fa(Request $request)
    {
        $user = $request->user();
        $user->two_factor_enabled = false;
        $user->two_factor_type = null;
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->save();
        return response()->json(['message' => '2FA disabled']);
    }
}
