<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use App\Models\User;
use App\Models\Wallet;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        return response()->json(User::with('wallets')->paginate(20));
    }

    public function kycRequests()
    {
        return response()->json(KycDocument::where('status', 'pending')->with('user')->get());
    }

    public function approveKyc(Request $request, $id)
    {
        $doc = KycDocument::findOrFail($id);
        $doc->update(['status' => 'approved', 'verified_by' => $request->user()->id]);
        
        $doc->user->update(['kyc_status' => 'approved']);

        AuditLog::create([
            'admin_id' => $request->user()->id,
            'action' => 'kyc_approve',
            'metadata' => ['document_id' => $doc->id, 'user_id' => $doc->user_id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['message' => 'KYC Approved']);
    }

    public function rejectKyc(Request $request, $id)
    {
        $doc = KycDocument::findOrFail($id);
        $doc->update(['status' => 'rejected', 'rejection_reason' => $request->reason, 'verified_by' => $request->user()->id]);
        
        $doc->user->update(['kyc_status' => 'rejected']);

        AuditLog::create([
            'admin_id' => $request->user()->id,
            'action' => 'kyc_reject',
            'metadata' => ['document_id' => $doc->id, 'user_id' => $doc->user_id, 'reason' => $request->reason],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['message' => 'KYC Rejected']);
    }

    public function freezeAccount(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        foreach ($user->wallets as $wallet) {
            $wallet->update(['is_frozen' => true]);
        }
        
        AuditLog::create([
            'admin_id' => $request->user()->id,
            'action' => 'account_freeze',
            'metadata' => ['user_id' => $user->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['message' => 'Account frozen']);
    }
}
