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
use App\Models\Wallet;
use App\Services\OtpService;
use App\Services\SettingsService;
use App\Mail\TestEmailMail;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function users(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('mobile_phone', 'like', '%' . $q . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', compact('users', 'q'));
    }

    public function showUser($id)
    {
        $user = User::with(['wallets', 'kyc_documents'])->findOrFail($id);
        $devices = DeviceLog::where('user_id', $user->id)->latest('last_active_at')->take(20)->get();
        $logins = LoginLog::where('user_id', $user->id)->latest('created_at')->take(20)->get();

        return view('admin.user-show', compact('user', 'devices', 'logins'));
    }

    public function editUser($id)
    {
        $user = User::with(['wallets'])->findOrFail($id);
        return view('admin.user-edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::with(['wallets'])->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'mobile_phone' => 'required|string|max:255|unique:users,mobile_phone,' . $user->id,
            'country_code' => 'required|string|size:2',
            'kyc_status' => 'required|string',
            'is_flagged' => 'nullable|boolean',
            'two_factor_enabled' => 'nullable|boolean',
            'two_factor_type' => 'nullable|string',
            'new_password' => 'nullable|string|min:12',
            'new_pin' => 'nullable|digits:4',
            'freeze_wallets' => 'nullable|boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->mobile_phone = $validated['mobile_phone'];
        $user->country_code = strtoupper($validated['country_code']);
        $user->kyc_status = $validated['kyc_status'];
        $user->is_flagged = (bool) ($validated['is_flagged'] ?? false);

        $user->two_factor_enabled = (bool) ($validated['two_factor_enabled'] ?? false);
        $user->two_factor_type = $validated['two_factor_type'] ?? null;

        $invalidateSessions = false;
        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
            $invalidateSessions = true;
        }

        if (!empty($validated['new_pin'])) {
            $user->security_pin = Hash::make($validated['new_pin']);
            $invalidateSessions = true;
        }

        if ($invalidateSessions) {
            $user->session_version = (int) ($user->session_version ?? 1) + 1;
        }

        $user->save();

        if (!empty($validated['freeze_wallets'])) {
            Wallet::where('user_id', $user->id)->update(['is_frozen' => true]);
        }

        $admin = auth('admin')->user();
        AuditLog::create([
            'admin_id' => $admin?->id,
            'action' => 'user_updated',
            'metadata' => ['user_id' => $user->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.show', $user->id)->with('success', 'Usuario actualizado.');
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        Wallet::where('user_id', $user->id)->update(['is_frozen' => true]);
        $user->session_version = (int) ($user->session_version ?? 1) + 1;
        $user->save();
        $user->delete();

        $admin = auth('admin')->user();
        AuditLog::create([
            'admin_id' => $admin?->id,
            'action' => 'user_deleted',
            'metadata' => ['user_id' => $user->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado.');
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

    public function integrationsSettings()
    {
        $settings = app(SettingsService::class);

        return view('admin.settings.integrations', [
            'mail_host' => $settings->get('mail.host'),
            'mail_port' => $settings->get('mail.port'),
            'mail_username' => $settings->get('mail.username'),
            'mail_encryption' => $settings->get('mail.encryption'),
            'mail_from_address' => $settings->get('mail.from_address'),
            'mail_from_name' => $settings->get('mail.from_name'),
            'sms_driver' => $settings->get('sms.driver'),
            'twilio_account_sid' => $settings->get('twilio.account_sid'),
            'twilio_from' => $settings->get('twilio.from'),
        ]);
    }

    public function saveIntegrations(Request $request)
    {
        $validated = $request->validate([
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:20',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'sms_driver' => 'nullable|string|max:50',
            'twilio_account_sid' => 'nullable|string|max:255',
            'twilio_auth_token' => 'nullable|string|max:255',
            'twilio_from' => 'nullable|string|max:50',
        ]);

        $admin = auth('admin')->user();
        $adminId = $admin?->id;
        $settings = app(SettingsService::class);

        $settings->set('mail.host', $validated['mail_host'] ?? null, false, $adminId);
        $settings->set('mail.port', isset($validated['mail_port']) ? (string) $validated['mail_port'] : null, false, $adminId);
        $settings->set('mail.username', $validated['mail_username'] ?? null, false, $adminId);
        $settings->set('mail.encryption', $validated['mail_encryption'] ?? null, false, $adminId);
        $settings->set('mail.from_address', $validated['mail_from_address'] ?? null, false, $adminId);
        $settings->set('mail.from_name', $validated['mail_from_name'] ?? null, false, $adminId);

        if (!empty($validated['mail_password'])) {
            $settings->set('mail.password', $validated['mail_password'], true, $adminId);
        }

        $settings->set('sms.driver', $validated['sms_driver'] ?? null, false, $adminId);
        $settings->set('twilio.account_sid', $validated['twilio_account_sid'] ?? null, false, $adminId);
        $settings->set('twilio.from', $validated['twilio_from'] ?? null, false, $adminId);

        if (!empty($validated['twilio_auth_token'])) {
            $settings->set('twilio.auth_token', $validated['twilio_auth_token'], true, $adminId);
        }

        AuditLog::create([
            'admin_id' => $adminId,
            'action' => 'integrations_updated',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Configuración guardada.');
    }

    public function testEmail(Request $request)
    {
        $validated = $request->validate(['to' => 'required|email|max:255']);

        app(SettingsService::class)->applyMailConfig();
        Mail::to($validated['to'])->send(new TestEmailMail('Prueba SMTP', 'Este es un correo de prueba enviado desde Saldo.'));

        return back()->with('success', 'Correo de prueba enviado.');
    }

    public function testSms(Request $request)
    {
        $validated = $request->validate([
            'to' => 'required|string|max:50',
        ]);

        app(OtpService::class)->sendTestSms($validated['to'], 'Saldo: SMS de prueba.');

        return back()->with('success', 'SMS de prueba enviado.');
    }
}
