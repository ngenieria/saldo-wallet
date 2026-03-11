<?php

namespace App\Services;

use App\Models\OneTimeCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function issueSms(User $user, string $purpose, ?int $ttlMinutes = 10): void
    {
        $ttlMinutes = $ttlMinutes ?? 10;

        $code = (string) random_int(100000, 999999);

        OneTimeCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);

        $to = $this->toE164($user->mobile_phone);

        $message = "Saldo OTP: {$code}. Expira en {$ttlMinutes} minutos.";
        $this->sendSms($to, $message);
    }

    public function verify(User $user, string $purpose, string $code, int $maxAttempts = 5): bool
    {
        $record = OneTimeCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('id')
            ->first();

        if (!$record) {
            return false;
        }

        if ($record->attempts >= $maxAttempts) {
            return false;
        }

        if (!Hash::check($code, $record->code)) {
            $record->increment('attempts');
            return false;
        }

        $record->consumed_at = now();
        $record->save();

        return true;
    }

    public function canResend(User $user, string $purpose, int $cooldownSeconds = 60): bool
    {
        $latest = OneTimeCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->orderByDesc('id')
            ->first();

        if (!$latest) {
            return true;
        }

        return $latest->created_at->diffInSeconds(now()) >= $cooldownSeconds;
    }

    private function toE164(?string $raw): string
    {
        $raw = trim((string) $raw);

        if ($raw === '') {
            return $raw;
        }

        if (str_starts_with($raw, '+')) {
            return $raw;
        }

        $defaultDial = env('SMS_DEFAULT_DIAL_CODE', '+57');

        $raw = preg_replace('/\D+/', '', $raw) ?? $raw;
        $defaultDial = preg_replace('/\D+/', '', (string) $defaultDial) ?? '';

        return '+' . $defaultDial . $raw;
    }

    private function sendSms(string $to, string $message): void
    {
        $driver = env('SMS_DRIVER', 'log');

        if ($driver === 'twilio') {
            $sid = (string) env('TWILIO_ACCOUNT_SID');
            $token = (string) env('TWILIO_AUTH_TOKEN');
            $from = (string) env('TWILIO_FROM');

            if ($sid === '' || $token === '' || $from === '') {
                Log::warning('SMS driver=twilio configured but credentials are missing.');
                Log::info('OTP SMS (fallback log)', ['to' => $to, 'message' => $message]);
                return;
            }

            $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
            $resp = Http::asForm()
                ->withBasicAuth($sid, $token)
                ->post($url, [
                    'From' => $from,
                    'To' => $to,
                    'Body' => $message,
                ]);

            if (!$resp->successful()) {
                Log::error('Twilio SMS send failed', [
                    'status' => $resp->status(),
                    'body' => $resp->body(),
                ]);
            }

            return;
        }

        Log::info('OTP SMS', ['to' => $to, 'message' => $message, 'driver' => $driver]);
    }
}

