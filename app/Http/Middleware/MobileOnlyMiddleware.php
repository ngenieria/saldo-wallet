<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MobileOnlyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $ua = strtolower((string) $request->userAgent());

        $isPhone = str_contains($ua, 'iphone')
            || str_contains($ua, 'ipod')
            || (str_contains($ua, 'android') && str_contains($ua, 'mobile'))
            || str_contains($ua, 'windows phone');

        if (!$isPhone) {
            return response()->view('mobile-only', [], 200);
        }

        return $next($request);
    }
}

