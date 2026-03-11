<?php

namespace App\Http\Middleware;

use App\Support\Jwt;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->header('Authorization');
        if (!$auth || !str_starts_with($auth, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token = substr($auth, 7);
        $secret = config('app.key');
        $payload = Jwt::decode($token, $secret);
        if (!$payload || !isset($payload['sub'])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::find($payload['sub']);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        auth()->setUser($user);
        return $next($request);
    }
}

