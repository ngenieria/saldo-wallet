<?php

namespace App\Services;

use App\Models\FraudAlert;
use App\Models\AmlFlag;
use App\Models\Transaction;
use App\Models\User;
use App\Models\DeviceLog;
use Illuminate\Support\Facades\DB;

class FraudService
{
    public function analyze(User $user, Transaction $transaction): void
    {
        $this->checkLargeAmount($user, $transaction);
        $this->checkRapidTransfers($user);
        $this->checkRepeatedCounterparty($user);
        $this->checkNewDeviceTransaction($user);
    }

    protected function checkLargeAmount(User $user, Transaction $transaction): void
    {
        $limit = 10000;
        if ($transaction->amount >= $limit) {
            FraudAlert::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'type' => 'large_amount',
                'severity' => 'high',
            ]);
            AmlFlag::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'type' => 'large_volume_transfer',
                'status' => 'pending',
            ]);
        }
    }

    protected function checkRapidTransfers(User $user): void
    {
        $count = Transaction::whereIn('sender_wallet_id', $user->wallets->pluck('id'))
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();
        if ($count >= 5) {
            FraudAlert::create([
                'user_id' => $user->id,
                'type' => 'rapid_transfers',
                'severity' => 'medium',
            ]);
        }
    }

    protected function checkRepeatedCounterparty(User $user): void
    {
        $rows = Transaction::select('receiver_wallet_id', DB::raw('COUNT(*) as c'))
            ->whereIn('sender_wallet_id', $user->wallets->pluck('id'))
            ->where('created_at', '>=', now()->subDays(1))
            ->groupBy('receiver_wallet_id')
            ->having('c', '>=', 3)
            ->get();
        if ($rows->count() > 0) {
            AmlFlag::create([
                'user_id' => $user->id,
                'type' => 'repeated_transfers_same_account',
                'status' => 'pending',
            ]);
        }
    }

    protected function checkNewDeviceTransaction(User $user): void
    {
        $newDevice = DeviceLog::where('user_id', $user->id)
            ->where('is_trusted', false)
            ->orderByDesc('last_active_at')
            ->first();
        if ($newDevice) {
            FraudAlert::create([
                'user_id' => $user->id,
                'type' => 'transaction_from_new_device',
                'severity' => 'medium',
            ]);
        }
    }
}

