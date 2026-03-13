<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class SettingsService
{
    private ?array $cache = null;

    public function get(string $key, ?string $default = null): ?string
    {
        $all = $this->all();
        if (!array_key_exists($key, $all)) {
            return $default;
        }

        $row = $all[$key];
        $value = $row['value'];

        if ($value === null) {
            return $default;
        }

        if ($row['is_encrypted']) {
            try {
                return Crypt::decryptString($value);
            } catch (\Throwable $e) {
                return $default;
            }
        }

        return $value;
    }

    public function set(string $key, ?string $value, bool $encrypt = false, ?int $adminId = null): void
    {
        $storedValue = $value;
        if ($value !== null && $encrypt) {
            $storedValue = Crypt::encryptString($value);
        }

        SystemSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'is_encrypted' => $encrypt,
                'updated_by_admin_id' => $adminId,
            ]
        );

        $this->cache = null;
    }

    public function applyMailConfig(): void
    {
        config(['mail.default' => 'smtp']);

        $host = $this->get('mail.host');
        $port = $this->get('mail.port');
        $username = $this->get('mail.username');
        $password = $this->get('mail.password');
        $encryption = $this->get('mail.encryption');
        $fromAddress = $this->get('mail.from_address');
        $fromName = $this->get('mail.from_name');

        if ($host) {
            config(['mail.mailers.smtp.host' => $host]);
        }
        if ($port) {
            config(['mail.mailers.smtp.port' => (int) $port]);
        }
        if ($username !== null) {
            config(['mail.mailers.smtp.username' => $username]);
        }
        if ($password !== null) {
            config(['mail.mailers.smtp.password' => $password]);
        }
        if ($encryption !== null) {
            config(['mail.mailers.smtp.encryption' => $encryption === '' ? null : $encryption]);
        }
        if ($fromAddress) {
            config(['mail.from.address' => $fromAddress]);
        }
        if ($fromName) {
            config(['mail.from.name' => $fromName]);
        }
    }

    private function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        try {
            if (!Schema::hasTable('system_settings')) {
                $this->cache = [];
                return $this->cache;
            }

            $rows = SystemSetting::query()->get(['key', 'value', 'is_encrypted'])->all();
            $map = [];
            foreach ($rows as $row) {
                $map[$row->key] = [
                    'value' => $row->value,
                    'is_encrypted' => (bool) $row->is_encrypted,
                ];
            }

            $this->cache = $map;
            return $this->cache;
        } catch (\Throwable $e) {
            $this->cache = [];
            return $this->cache;
        }
    }
}
