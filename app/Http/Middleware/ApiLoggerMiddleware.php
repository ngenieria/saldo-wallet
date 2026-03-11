<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;

class ApiLoggerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = (int) ((microtime(true) - $start) * 1000);

        ApiLog::create([
            'user_id' => optional($request->user())->id,
            'method' => $request->method(),
            'path' => $request->path(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'created_at' => now(),
        ]);

        return $response;
    }
}

