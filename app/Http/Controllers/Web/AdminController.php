<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdminIpAllowlist;
use App\Models\AdminLoginLog;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Transaction;
use App\Models\KycDocument;
use App\Models\FraudAlert;
use App\Models\AmlFlag;
use App\Models\LoginLog;
use App\Models\DeviceLog;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_transactions' => Transaction::count(),
            'pending_kyc' => KycDocument::where('status', 'pending')->count(),
        ];
        
        $recent_users = User::latest()->take(5)->get();
        $recent_transactions = Transaction::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_transactions'));
    }

    public function users()
    {
        $users = User::paginate(20);
        return view('admin.users', compact('users'));
    }

    public function kyc()
    {
        $documents = KycDocument::where('status', 'pending')->with('user')->get();
        return view('admin.kyc', compact('documents'));
    }

    public function approveKyc(Request $request, $id)
    {
        $admin = auth('admin')->user();
        $doc = KycDocument::findOrFail($id);
        $doc->update(['status' => 'approved', 'verified_by' => $admin?->id]);
        
        $doc->user->update(['kyc_status' => 'approved']);

        if ($admin) {
            AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'kyc_approve',
                'metadata' => ['document_id' => $doc->id, 'user_id' => $doc->user_id],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'KYC Approved');
    }

    public function rejectKyc(Request $request, $id)
    {
        $admin = auth('admin')->user();
        $doc = KycDocument::findOrFail($id);
        $doc->update(['status' => 'rejected', 'rejection_reason' => $request->reason, 'verified_by' => $admin?->id]);
        
        $doc->user->update(['kyc_status' => 'rejected']);

        if ($admin) {
            AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'kyc_reject',
                'metadata' => ['document_id' => $doc->id, 'user_id' => $doc->user_id, 'reason' => $request->reason],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->with('success', 'KYC Rejected');
    }

    public function security()
    {
        $fraud = FraudAlert::latest()->take(20)->get();
        $aml = AmlFlag::latest()->take(20)->get();
        $suspiciousLogins = LoginLog::where('status', 'failed')->latest()->take(20)->get();
        $newDevices = DeviceLog::where('is_trusted', false)->latest()->take(20)->get();
        $largeTx = Transaction::where('amount', '>=', 10000)->latest()->take(20)->get();
        $adminLoginFails = AdminLoginLog::where('status', 'failed')->latest('created_at')->take(20)->get();
        return view('admin.security', compact('fraud', 'aml', 'suspiciousLogins', 'newDevices', 'largeTx', 'adminLoginFails'));
    }

    public function securitySettings()
    {
        $admin = auth('admin')->user();
        $ips = AdminIpAllowlist::where('admin_id', $admin->id)->orderBy('ip_address')->get();
        return view('admin.settings.security', compact('admin', 'ips'));
    }

    public function startTotpSetup(Request $request)
    {
        $admin = auth('admin')->user();
        $secret = Totp::generateSecret();
        $admin->two_factor_type = 'totp';
        $admin->two_factor_secret = encrypt($secret);
        $admin->two_factor_enabled = false;
        $admin->save();

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_2fa_totp_setup_started',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('totp_secret', $secret);
    }

    public function verifyTotpSetup(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $admin = auth('admin')->user();
        if (!$admin->two_factor_secret || $admin->two_factor_type !== 'totp') {
            return back()->withErrors(['code' => 'No hay configuración TOTP pendiente.']);
        }

        $secret = decrypt($admin->two_factor_secret);
        if (!Totp::verify($secret, (string) $request->code)) {
            return back()->withErrors(['code' => 'Código inválido.']);
        }

        $admin->two_factor_enabled = true;
        $admin->two_factor_type = 'totp';
        $admin->save();

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_2fa_enabled',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['type' => 'totp'],
        ]);

        return back()->with('success', '2FA habilitado.');
    }

    public function disable2fa(Request $request)
    {
        $admin = auth('admin')->user();
        $admin->two_factor_enabled = false;
        $admin->two_factor_type = null;
        $admin->two_factor_secret = null;
        $admin->save();

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_2fa_disabled',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', '2FA deshabilitado.');
    }

    public function enableIpAllowlist(Request $request)
    {
        $admin = auth('admin')->user();
        $admin->ip_allowlist_enabled = true;
        $admin->save();

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_ip_allowlist_enabled',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'IP allowlist habilitado.');
    }

    public function disableIpAllowlist(Request $request)
    {
        $admin = auth('admin')->user();
        $admin->ip_allowlist_enabled = false;
        $admin->save();

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_ip_allowlist_disabled',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'IP allowlist deshabilitado.');
    }

    public function addAllowlistIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'label' => 'nullable|string|max:255',
        ]);

        $admin = auth('admin')->user();

        AdminIpAllowlist::updateOrCreate(
            ['admin_id' => $admin->id, 'ip_address' => $request->ip_address],
            ['label' => $request->label, 'is_active' => true]
        );

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_ip_allowlist_added',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['allowed_ip' => $request->ip_address],
        ]);

        return back()->with('success', 'IP agregada.');
    }

    public function deleteAllowlistIp(Request $request, $id)
    {
        $admin = auth('admin')->user();

        $row = AdminIpAllowlist::where('admin_id', $admin->id)->where('id', $id)->firstOrFail();
        $ip = $row->ip_address;
        $row->delete();

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_ip_allowlist_removed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['allowed_ip' => $ip],
        ]);

        return back()->with('success', 'IP eliminada.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:12|confirmed',
        ]);

        $admin = auth('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Password actual inválido.']);
        }

        $admin->password = Hash::make($request->password);
        $admin->session_version = (int) ($admin->session_version ?? 1) + 1;
        $admin->save();

        session(['session_version:admin' => (int) $admin->session_version]);

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_password_changed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Password actualizado. Sesiones previas quedarán invalidadas.');
    }
}
