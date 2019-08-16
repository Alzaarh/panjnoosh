<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\User;
use Carbon\Carbon;
use App\Utils\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use App\Utils\Errors;

class Auth {
    use Response, Errors;
    public function handle(Request $request, Closure $next) {
        $token = $request->header('Authorization');
        if(!$token) {
            throw new AuthorizationException();
        }
        $decoded = JWT::decode($token, env('JWT_KEY'), ['HS256']);
        if(!(new Carbon($decoded->expireAt))->greaterThan(Carbon::now())) {
            throw new AuthorizationException();            
        }
        $user = User::where('username', $decoded->username)->first();
        if(!$user){
            throw ValidationException::withMessages(['token' => $this->badToken]);
        }
        $request->user = $user;        
        return $next($request);
    }
}
