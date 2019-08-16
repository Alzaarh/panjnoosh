<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Carbon\Carbon;
use App\Http\Resources\User as UserResource;
use App\Utils\Errors;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    use Errors;
    //Register user
    public function signup(Request $request) {
        $validInput = $this->validateSignup($request);
        $userData = $this->transform($validInput);
        $user = User::create($userData);
        $user->token = $this->createAuthToken($user);
        return (new UserResource($user))->response(201);
    }
    //Validate user input for registration
    private function validateSignup(Request $request) {
        $rules = [
            'username' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-z0-9.-]*$/',
                'unique:users,username',
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:5',
                'max:30',
                'regex:/^[0-9a-zA-zالف-ی.-_!]*$/',
                'confirmed',
            ],
        ];
        return $this->validate($request, $rules, [
            'username.*' => $this->badUsername,
            'password.*' => $this->badPassword
        ]);
    }
    //Transform user input to database accepted input
    private function transform(array $input) {
        return [
            'username' => $input['username'],
            'password' => $input['password'],
        ];
    }
    //Create JWT token
    private function createAuthToken(User $user) {
        $key = env('JWT_KEY');
        $payload = [
            'username' => $user->username,
            'createdAt' => Carbon::now(),
            'expireAt' => Carbon::now()->addDay(),
        ];
        return  JWT::encode($payload, $key);
    }
    //Login user
    public function signin(Request $request) {
        $validInput = $this->validateSignin($request);
        $userData = $this->transform($validInput);
        $user = User::where('username', $userData['username'])->first();
        if(!$user || !Hash::check($userData['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => $this->badRequest,
            ]);
        }
        $user->token = $this->createAuthToken($user);
        return (new UserResource($user));
    }
    //Validate user input for login
    private function validateSignin(Request $request) {
        $rules = [
            'username' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-z0-9.-]*$/',
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:5',
                'max:30',
                'regex:/^[0-9a-zA-zالف-ی.-_!]*$/',
            ],
        ];
        return $this->validate($request, $rules, [
            'username.*' => $this->badUsername,
            'password.*' => $this->badPassword
        ]);
    }
}
