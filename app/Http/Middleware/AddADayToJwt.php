<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class AddADayToJwt
{
    private const REDIS_KEY = 'user';

    public function handle(Request $request, Closure $next)
    {
        Redis::SET(self::REDIS_KEY . ':' . $request->auth->userId, Carbon::now()->addDay());

        return $next($request);
    }
}