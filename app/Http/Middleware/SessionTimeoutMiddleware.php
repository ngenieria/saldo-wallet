<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionTimeoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = 900;
        $lastActivity = session('last_activity');
        if ($lastActivity && time() - $lastActivity > $timeout) {
            if (auth('admin')->check()) {
                auth('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login');
            }

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }
        session(['last_activity' => time()]);
        return $next($request);
    }
}
