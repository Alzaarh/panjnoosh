<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Carbon\Carbon;
use App\Http\Resources\User as UserResource;
use App\Utils\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use Response;

    public function signup(Request $request)
    {
        $input = $this->validateSignup($request);

        $userData = $this->transform($input);

        $user = User::create($userData);

        $user->token = $this->createAuthToken($user);

        return (new UserResource($user))->response(201);
    }

    public function signin(Request $request)
    {
        $input = $this->validateSignin($request);

        $userData = $this->transform($input);

        $user = User::where('username', $userData['username'])->first();

        if(!$user)
        {
            return $this->unprocEntity();
        }

        if(!Hash::check($userData['password'], $user->password))
        {
            return $this->unprocEntity('passwords do not match');
        }

        $user->token = $this->createAuthToken($user);

        return (new UserResource($user));
    }

    private function validateSignup(Request $request)
    {
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

        return $this->validate($request, $rules);
    }

    private function validateSignin(Request $request)
    {
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

        return $this->validate($request, $rules);
    }

    private function transform(array $input)
    {
        return [
            'username' => $input['username'],
            'password' => $input['password'],
        ];
    }

    private function createAuthToken(User $user)
    {
        $key = env('JWT_KEY');

        $payload = [
            'username' => $user->username,
            'createdAt' => Carbon::now(),
            'expireAt' => Carbon::now()->addDay(),
        ];

        return  JWT::encode($payload, $key);
    }
}
