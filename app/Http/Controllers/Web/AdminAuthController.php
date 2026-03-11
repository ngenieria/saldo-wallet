<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLoginLog;
use App\Models\AuditLog;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            if ($admin) {
                $admin->increment('failed_login_attempts');
                if ($admin->failed_login_attempts >= 5) {
                    $admin->account_locked_until = now()->addMinutes(15);
                    $admin->save();
                }
                AdminLoginLog::create([
                    'admin_id' => $admin->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed',
                    'created_at' => now(),
                ]);
                AuditLog::create([
                    'admin_id' => $admin->id,
                    'action' => 'admin_login_failed',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => ['reason' => 'invalid_credentials'],
                ]);
            }

            return back()->withErrors(['email' => 'Credenciales inválidas.'])->onlyInput('email');
        }

        if ($admin->account_locked_until && now()->lessThan($admin->account_locked_until)) {
            return back()->withErrors(['email' => 'Cuenta bloqueada temporalmente.'])->onlyInput('email');
        }

        if ($admin->two_factor_enabled && $admin->two_factor_type === 'totp') {
            session([
                'pending_admin_id' => $admin->id,
            ]);

            return redirect()->route('admin.verify.form');
        }

        auth('admin')->login($admin);
        $request->session()->regenerate();

        $admin->failed_login_attempts = 0;
        $admin->last_login_at = now();
        $admin->last_login_ip = $request->ip();
        $admin->save();

        session(['session_version:admin' => (int) ($admin->session_version ?? 1)]);

        AdminLoginLog::create([
            'admin_id' => $admin->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'created_at' => now(),
        ]);

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function showVerifyForm()
    {
        $adminId = session('pending_admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $adminId = session('pending_admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = Admin::find($adminId);
        if (!$admin || !$admin->two_factor_enabled || $admin->two_factor_type !== 'totp' || !$admin->two_factor_secret) {
            return redirect()->route('admin.login');
        }

        $secret = decrypt($admin->two_factor_secret);
        if (!Totp::verify($secret, (string) $request->code)) {
            return back()->withErrors(['code' => 'Código inválido.']);
        }

        auth('admin')->login($admin);
        $request->session()->regenerate();
        session()->forget('pending_admin_id');

        $admin->failed_login_attempts = 0;
        $admin->last_login_at = now();
        $admin->last_login_ip = $request->ip();
        $admin->save();

        session(['session_version:admin' => (int) ($admin->session_version ?? 1)]);

        AdminLoginLog::create([
            'admin_id' => $admin->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'created_at' => now(),
        ]);

        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'admin_2fa_verified',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $admin = auth('admin')->user();
        if ($admin) {
            AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'admin_logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        auth('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

