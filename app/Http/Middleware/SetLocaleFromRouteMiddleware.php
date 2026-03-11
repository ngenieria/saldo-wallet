<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromRouteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');

        if (is_string($locale) && preg_match('/^[a-z]{2}-[A-Z]{2}$/', $locale)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}

