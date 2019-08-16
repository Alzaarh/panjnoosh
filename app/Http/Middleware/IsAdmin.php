<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class IsAdmin {
    public function handle($request, Closure $next) {
        if(!($request->user->role == 'admin')) {
            throw new AuthorizationException();
        }
        $response = $next($request);
        return $response;
    }
}
