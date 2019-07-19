<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Firebase\JWT\JWT;

class AddADayToJwt
{
    public function handle(Request $request, Closure $next)
    {
        dd($request->auth);
        return $next($request);
    }
}