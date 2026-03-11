<?php

namespace App\Services;

use App\Mail\OtpCodeMail;
use App\Models\OneTimeCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function issue(User $user, string $purpose, array $channels = ['sms'], ?int $ttlMinutes = 10, int $digits = 4): void
    {
        $ttlMinutes = $ttlMinutes ?? 10;

        $code = $this->generateCode($digits);

        OneTimeCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);

        $channels = array_values(array_unique($channels));

        if (in_array('sms', $channels, true)) {
            $to = $this->toE164($user->mobile_phone);
            if ($to !== '') {
                $message = "Saldo: tu código es {$code}. Expira en {$ttlMinutes} minutos.";
                $this->sendSms($to, $message);
            }
        }

        if (in_array('email', $channels, true)) {
            if ((string) $user->email !== '') {
                app(SettingsService::class)->applyMailConfig();
                Mail::to($user->email)->send(new OtpCodeMail($user->name, $code, $ttlMinutes));
            }
        }
    }

    public function issueSms(User $user, string $purpose, ?int $ttlMinutes = 10): void
    {
        $this->issue($user, $purpose, ['sms'], $ttlMinutes, 4);
    }

    public function issueEmail(User $user, string $purpose, ?int $ttlMinutes = 10): void
    {
        $this->issue($user, $purpose, ['email'], $ttlMinutes, 4);
    }

    public function issueSmsAndEmail(User $user, string $purpose, ?int $ttlMinutes = 10): void
    {
        $this->issue($user, $purpose, ['sms', 'email'], $ttlMinutes, 4);
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

    public function sendTestSms(string $to, string $message): void
    {
        $to = $this->toE164($to);
        if ($to === '') {
            return;
        }
        $this->sendSms($to, $message);
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
        $settings = app(SettingsService::class);
        $driver = $settings->get('sms.driver', env('SMS_DRIVER', 'log')) ?? 'log';

        if ($driver === 'twilio') {
            $sid = (string) ($settings->get('twilio.account_sid') ?? env('TWILIO_ACCOUNT_SID'));
            $token = (string) ($settings->get('twilio.auth_token') ?? env('TWILIO_AUTH_TOKEN'));
            $from = (string) ($settings->get('twilio.from') ?? env('TWILIO_FROM'));

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

    private function generateCode(int $digits): string
    {
        $digits = max(4, min(8, $digits));
        $max = (10 ** $digits) - 1;
        $n = random_int(0, $max);
        return str_pad((string) $n, $digits, '0', STR_PAD_LEFT);
    }
}
