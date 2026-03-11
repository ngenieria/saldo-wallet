<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\ExchangeRate;
use App\Models\Fee;
use App\Models\TransactionLimit;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class WalletService
{
    public function transfer(User $sender, string $recipientIdentifier, float $amount, string $currency, string $pin, ?string $idempotencyKey = null)
    {
        if (!Hash::check($pin, $sender->security_pin)) {
            throw new Exception('Invalid Security PIN');
        }

        $recipient = User::where('email', $recipientIdentifier)
            ->orWhere('mobile_phone', $recipientIdentifier)
            ->first();

        if (!$recipient) {
            throw new Exception('Recipient not found');
        }

        if ($sender->id === $recipient->id) {
            throw new Exception('Cannot transfer to yourself');
        }

        $senderWallet = $sender->wallets()->where('currency', $currency)->first();
        if (!$senderWallet) {
            throw new Exception('Wallet not found');
        }

        $recipientWallet = $recipient->wallets()->firstOrCreate(
            ['currency' => $currency],
            ['balance' => 0]
        );

        $limits = TransactionLimit::where(function ($q) use ($sender) {
            $q->where('is_global', true)->orWhere('user_id', $sender->id);
        })->where(function ($q) use ($currency) {
            $q->whereNull('currency')->orWhere('currency', $currency);
        })->orderByRaw('user_id is null')->first();

        if ($limits && $limits->per_transaction_limit && $amount > $limits->per_transaction_limit) {
            throw new Exception('Amount exceeds per-transaction limit');
        }

        if ($limits && $limits->daily_limit) {
            $sumDay = Transaction::whereIn('sender_wallet_id', $sender->wallets->pluck('id'))
                ->where('currency', $currency)
                ->where('created_at', '>=', now()->startOfDay())
                ->sum('amount');
            if ($sumDay + $amount > $limits->daily_limit) {
                throw new Exception('Daily transfer limit exceeded');
            }
        }

        if ($limits && $limits->monthly_limit) {
            $sumMonth = Transaction::whereIn('sender_wallet_id', $sender->wallets->pluck('id'))
                ->where('currency', $currency)
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('amount');
            if ($sumMonth + $amount > $limits->monthly_limit) {
                throw new Exception('Monthly transfer limit exceeded');
            }
        }

        $fee = 0;
        $totalDeduction = $amount + $fee;

        return DB::transaction(function () use ($sender, $recipient, $senderWallet, $recipientWallet, $amount, $currency, $fee, $idempotencyKey, $totalDeduction) {
            $senderWallet = Wallet::where('id', $senderWallet->id)->lockForUpdate()->first();
            $recipientWallet = Wallet::where('id', $recipientWallet->id)->lockForUpdate()->first();

            if ($senderWallet->balance < $totalDeduction) {
                throw new Exception('Insufficient funds');
            }

            if ($idempotencyKey) {
                $exists = Transaction::where('reference', $idempotencyKey)->exists();
                if ($exists) {
                    throw new Exception('Duplicate transaction');
                }
            }

            $senderWallet->decrement('balance', $amount + $fee);

            $recipientWallet->increment('balance', $amount);

            $transaction = Transaction::create([
                'transaction_id' => 'TRX-' . uniqid(),
                'sender_wallet_id' => $senderWallet->id,
                'receiver_wallet_id' => $recipientWallet->id,
                'amount' => $amount,
                'currency' => $currency,
                'type' => 'transfer',
                'status' => 'completed',
                'fee' => $fee,
                'reference' => $idempotencyKey,
                'completed_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $sender->id,
                'action' => 'transfer',
                'metadata' => ['transaction_id' => $transaction->id, 'amount' => $amount, 'currency' => $currency],
            ]);

            app(\App\Services\FraudService::class)->analyze($sender, $transaction);

            return $transaction;
        });
    }

    public function exchange(User $user, string $fromCurrency, string $toCurrency, float $amount, string $pin)
    {
        if (!Hash::check($pin, $user->security_pin)) {
            throw new Exception('Invalid Security PIN');
        }

        $rateRecord = ExchangeRate::where('from_currency', $fromCurrency)
            ->where('to_currency', $toCurrency)
            ->first();

        if (!$rateRecord) {
             $reverseRate = ExchangeRate::where('from_currency', $toCurrency)
                ->where('to_currency', $fromCurrency)
                ->first();
             
             if ($reverseRate) {
                 $rate = 1 / $reverseRate->rate;
             } else {
                 throw new Exception("Exchange rate not found for $fromCurrency to $toCurrency");
             }
        } else {
            $rate = $rateRecord->rate;
        }

        $convertedAmount = $amount * $rate;

        $fromWallet = $user->wallets()->where('currency', $fromCurrency)->first();
        if (!$fromWallet || $fromWallet->balance < $amount) {
            throw new Exception('Insufficient funds');
        }

        $toWallet = $user->wallets()->firstOrCreate(
            ['currency' => $toCurrency],
            ['balance' => 0]
        );

        return DB::transaction(function () use ($user, $fromWallet, $toWallet, $amount, $convertedAmount, $fromCurrency, $toCurrency, $rate) {
            $fromWallet = Wallet::where('id', $fromWallet->id)->lockForUpdate()->first();
            $toWallet = Wallet::where('id', $toWallet->id)->lockForUpdate()->first();
            if ($fromWallet->balance < $amount) {
                throw new Exception('Insufficient funds');
            }
            $fromWallet->decrement('balance', $amount);
            $toWallet->increment('balance', $convertedAmount);

            $transaction = Transaction::create([
                'transaction_id' => 'EXC-' . uniqid(),
                'sender_wallet_id' => $fromWallet->id,
                'receiver_wallet_id' => $toWallet->id,
                'amount' => $amount,
                'currency' => $fromCurrency,
                'type' => 'exchange',
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'exchange',
                'metadata' => ['transaction_id' => $transaction->id, 'from' => $fromCurrency, 'to' => $toCurrency, 'rate' => $rate],
            ]);

            app(\App\Services\FraudService::class)->analyze($user, $transaction);

            return $transaction;
        });
    }

    public function processPayment(User $payer, string $qrToken, float $amount, string $pin)
    {
        if (!Hash::check($pin, $payer->security_pin)) {
            throw new Exception('Invalid Security PIN');
        }

        $qrCode = \App\Models\QrCode::where('token', $qrToken)->first();
        if (!$qrCode) {
            throw new Exception('Invalid QR Code');
        }

        if ($qrCode->expires_at && $qrCode->expires_at->isPast()) {
            throw new Exception('QR Code Expired');
        }

        if ($qrCode->amount && $qrCode->amount != $amount) {
            throw new Exception("Payment amount must be {$qrCode->amount} {$qrCode->currency}");
        }

        $recipient = $qrCode->user;
        if ($payer->id === $recipient->id) {
            throw new Exception('Cannot pay yourself');
        }

        $currency = $qrCode->currency;
        $payerWallet = $payer->wallets()->where('currency', $currency)->first();

        if (!$payerWallet || $payerWallet->balance < $amount) {
            throw new Exception('Insufficient funds');
        }

        $recipientWallet = $recipient->wallets()->firstOrCreate(
            ['currency' => $currency],
            ['balance' => 0]
        );

        return DB::transaction(function () use ($payer, $payerWallet, $recipientWallet, $amount, $currency, $qrCode) {
            $payerWallet = Wallet::where('id', $payerWallet->id)->lockForUpdate()->first();
            $recipientWallet = Wallet::where('id', $recipientWallet->id)->lockForUpdate()->first();
            if ($payerWallet->balance < $amount) {
                throw new Exception('Insufficient funds');
            }
            $payerWallet->decrement('balance', $amount);
            $recipientWallet->increment('balance', $amount);

            $transaction = Transaction::create([
                'transaction_id' => 'PAY-' . uniqid(),
                'sender_wallet_id' => $payerWallet->id,
                'receiver_wallet_id' => $recipientWallet->id,
                'amount' => $amount,
                'currency' => $currency,
                'type' => 'payment',
                'status' => 'completed',
                'qr_code_id' => $qrCode->id,
                'completed_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $payer->id,
                'action' => 'payment',
                'metadata' => ['transaction_id' => $transaction->id, 'amount' => $amount, 'currency' => $currency],
            ]);

            app(\App\Services\FraudService::class)->analyze($payer, $transaction);

            return $transaction;
        });
    }
}
