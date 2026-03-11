<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Exception;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'recipient' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'pin' => 'required|digits:4',
        ]);

        try {
            $this->walletService->transfer(
                $request->user(),
                $request->recipient,
                $request->amount,
                $request->currency,
                $request->pin
            );

            return back()->with('success', 'Transfer successful!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function exchange(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'amount' => 'required|numeric|min:0.01',
            'pin' => 'required|digits:4',
        ]);

        try {
            $this->walletService->exchange(
                $request->user(),
                $request->from_currency,
                $request->to_currency,
                $request->amount,
                $request->pin
            );

            return back()->with('success', 'Exchange successful!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
