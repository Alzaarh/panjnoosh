<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\CreateNewUserJob;
use Illuminate\Support\Facades\Redis;
use App\User;

class RegisterController extends Controller
{
    private function validateNewUser(Request $request)
    {
        $rules = [
            'name' => 'bail|required|string',
            'email' => 'bail|required|email',
            'password' => 'bail|required|string|min:5|confirmed',
            'address' => 'bail|required|string|',
            'zipcode' => 'bail|required|string|numeric',
            'phone' => 'bail|required|numeric',
        ];

        return $this->validate($request, $rules);
    }

    public function registerUser(Request $request)
    {
        $data = $this->validateNewUser($request);
        
        Redis::incr('user_count');

        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        $user->zipcode = $data['zipcode'];
        $user->password = $data['password'];

        Redis::hset('users', Redis::get('user_count'), $user);

        // return $this->dispatchNow(new CreateNewUserJob($data));
    }
}