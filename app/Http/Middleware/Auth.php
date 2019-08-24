<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class Auth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);
        if (!$token) {
            throw new AuthorizationException();
        }
        $decoded = JWT::decode($token, env('JWT_KEY'), ['HS256']);
        if (!(new Carbon($decoded->expireAt))->greaterThan(Carbon::now())) {
            throw new AuthorizationException();
        }
        $user = User::where('username', $decoded->username)->first();
        if (!$user) {
            throw new AuthorizationException();
        }
        $request->merge(['user' => $user]);
        return $next($request);
    }
}
