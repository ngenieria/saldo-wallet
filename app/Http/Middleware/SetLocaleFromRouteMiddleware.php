<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromRouteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');

        $allowed = ['es-CO', 'es-AR', 'es-MX', 'es-ES', 'en-US'];

        if (is_string($locale) && in_array($locale, $allowed, true)) {
            app()->setLocale($locale);
        } elseif (is_string($locale) && preg_match('/^[a-z]{2}-[A-Z]{2}$/', $locale)) {
            return redirect('/es-CO');
        }

        return $next($request);
    }
}
