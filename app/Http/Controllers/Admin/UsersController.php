<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Traits\ResponseTrait;

class UsersController extends Controller
{
    use ResponseTrait;

    private function validateNewUser(Request $request)
    {
        $rules = [
            'name' => 'bail|required|string',
            'email' => 'bail|required|email|unique:users,email',
            'password' => 'bail|required|string|min:5|confirmed',
            'address' => 'string',
            'zipcode' => 'string',
            'phone' => 'string',
            'role' => 'string',
        ];

        return $this->validate($request, $rules);
    }
    
    public function create(Request $request)
    {
        $data = $this->validateNewUser($request);

        $user = User::create($data);

        return $this->created('user created');
    }
}