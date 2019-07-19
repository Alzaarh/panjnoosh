<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Firebase\JWT\JWT;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Traits\ResponseTrait;


class LoginController extends Controller
{
    private const REDIS_KEY = 'user';

    use ResponseTrait;

    private function validateUser(Request $request)
    {
        $rules = [
            'email' => 'bail|required|email',
            'password' => 'bail|required|string|min:5',
        ];

        $data = $this->validate($request, $rules);

        $user = User::where('email', $data['email'])->firstOrFail();

        if(! Hash::check($data['password'], $user->password))
        {
            return response()->json(['errors' => 'validation error'], 400);
        }
        
        return $user;
    }

    private function jwt(User $user)
    {
        $payload = [
            'creationDate' => Carbon::now(),
            'expirationDate' => Carbon::now()->addDay(),
            'userId' => $user->id,
            'userEmail' => $user->email,
            'userRole' => $user->role,
        ];

        return JWT::encode($payload, env('JWT_KEY'));
    }

    public function LoginUser(Request $request)
    {
        $user = $this->validateUser($request);

        if(! $user instanceof User)
        {
            return $user;
        }
        
        $data['token'] = $this->jwt($user);

        $data['userId'] = $user->id;

        Redis::SET(self::REDIS_KEY . ':' . $user->id, json_encode($data));

        return $this->ok($data);
    }
}