<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class CheckIfAdmin
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        if($request->auth->userRole !== 'admin')
        {
            return $this->unauth();
        }

        return $next($request);
    }
}