<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSessionVersionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = $request->user();
            $key = 'session_version:web';
            $sessionVersion = session($key);
            $dbVersion = (int) ($user->session_version ?? 1);

            if ($sessionVersion === null) {
                session([$key => $dbVersion]);
            } elseif ((int) $sessionVersion !== $dbVersion) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login');
            }
        }

        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            $key = 'session_version:admin';
            $sessionVersion = session($key);
            $dbVersion = (int) ($admin->session_version ?? 1);

            if ($sessionVersion === null) {
                session([$key => $dbVersion]);
            } elseif ((int) $sessionVersion !== $dbVersion) {
                auth('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login');
            }
        }

        return $next($request);
    }
}

