<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\User;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $data = $request->header('Authorization');

        if(! $data)
        {
            return response()->json(['errors' => 'unauthorized'], 401);
        }

        $decoded = JWT::decode($data, env('JWT_KEY'), ['HS256']);

        $user = User::find($decoded->userId);

        if(! $user)
        {
            return response()->json(['errors' => 'unauthorized'], 401);
        }

        if($user->email !== $decoded->userEmail)
        {
            return response()->json(['errors' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
