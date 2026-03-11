<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index(Request $request)
    {
        return response()->json([
            'wallets' => $request->user()->wallets,
        ]);
    }

    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'pin' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $transaction = $this->walletService->transfer(
                $request->user(),
                $request->recipient,
                $request->amount,
                $request->currency,
                $request->pin,
                $request->header('Idempotency-Key')
            );

            return response()->json([
                'message' => 'Transfer successful',
                'transaction' => $transaction,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function exchange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'amount' => 'required|numeric|min:0.01',
            'pin' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $transaction = $this->walletService->exchange(
                $request->user(),
                $request->from_currency,
                $request->to_currency,
                $request->amount,
                $request->pin
            );

            return response()->json([
                'message' => 'Exchange successful',
                'transaction' => $transaction,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function transactions(Request $request)
    {
        $transactions = Transaction::where(function ($query) use ($request) {
            $query->whereIn('sender_wallet_id', $request->user()->wallets->pluck('id'))
                  ->orWhereIn('receiver_wallet_id', $request->user()->wallets->pluck('id'));
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return response()->json($transactions);
    }
}
