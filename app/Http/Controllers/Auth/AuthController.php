<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validInput = $this->validateSignup($request);
        $user = User::create($validInput);
        $user->token = $this->createAuthToken($user);
        return (new UserResource($user))->response(201);
    }
    private function validateSignup(Request $request)
    {
        return $this->validate($request, [
            'username' => 'required|string|alpha_dash|min:3|max:255|unique:users,username',
            'password' => 'required|string|min:5|max:255',
        ], [
            'username.unique' => 'نام کاربری قبلا استفاده شده',
            'username.*' => 'نام کاربری نامعتبر',
            'password.*' => 'رمزعبور نامعتبر',
        ]);
    }
    public function signin(Request $request)
    {
        $validInput = $this->validateSignin($request);
        $user = User::where('username', $validInput['username'])->first();
        if (!$user || !Hash::check($validInput['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => 'نام کاربری نامعتبر',
            ]);
        }
        $user->token = $this->createAuthToken($user);
        return (new UserResource($user));
    }
    private function createAuthToken(User $user)
    {
        $key = env('JWT_KEY');
        $payload = [
            'username' => $user->username,
            'createdAt' => Carbon::now(),
            'expireAt' => Carbon::now()->addDay(),
        ];
        return JWT::encode($payload, $key);
    }
    private function validateSignin(Request $request)
    {
        return $this->validate($request, [
            'username' => 'required|string|alpha_dash|min:3|max:255',
            'password' => 'required|string|min:5|max:255',
        ], [
            'username.*' => 'نام کاربری نامعتبر',
            'password.*' => 'رمزعبور نامعتبر',
        ]);
    }
}
