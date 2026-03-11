<?php

namespace App\Http\Middleware;

use App\Models\AdminIpAllowlist;
use Closure;
use Illuminate\Http\Request;

class AdminIpAllowlistMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('admin')->check()) {
            return $next($request);
        }

        $admin = auth('admin')->user();
        if (!$admin->ip_allowlist_enabled) {
            return $next($request);
        }

        $ip = $request->ip();

        $allowed = AdminIpAllowlist::where('admin_id', $admin->id)
            ->where('is_active', true)
            ->where('ip_address', $ip)
            ->exists();

        if (!$allowed) {
            return response()->view('admin.blocked', ['ip' => $ip], 403);
        }

        return $next($request);
    }
}

