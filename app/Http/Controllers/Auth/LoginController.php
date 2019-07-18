<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Firebase\JWT\JWT;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
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
        return response()->json(['data' => ['token' => $this->jwt($user), 'userId' => $user->id]], 200);
    }
}