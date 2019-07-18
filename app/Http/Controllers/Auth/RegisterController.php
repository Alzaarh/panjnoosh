<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\CreateNewUserJob;


class RegisterController extends Controller
{
    private function validateNewUser(Request $request)
    {
        $rules = [
            'name' => 'bail|required|string',
            'email' => 'bail|required|email|unique:users,email',
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
        
        return $this->dispatchNow(new CreateNewUserJob($data));
    }
}