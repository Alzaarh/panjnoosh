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
            'role' => 'bail|required|string|in:admin,user',
        ];

        return $this->validate($request, $rules);
    }

    private function validateQueryString(Request $request)
    {
        $rules = [
            'count' => 'bail|numeric|gte:1',
            'filter' => 'in:id,name,email,created_at',
            'sort' => 'in:asc,desc', 
        ];

        return $this->validate($request, $rules);
    }

    public function index(Request $request)
    {
        $data = $this->validateQueryString($request);

        $filter = $data['filter'] ? $data['filter'] : 'id';

        $sort = $data['sort'] ? $data['sort'] : 'asc';

        if(isset($data['count']))
        {
            $users = User::orderBy($filter, $sort)->take($data['count'])->get();
        }
        else
        {
            $users = User::orderBy($filter, $sort)->get();
        }

        return $this->ok($users);
    }
    
    public function create(Request $request)
    {
        $data = $this->validateNewUser($request);

        $user = User::create($data);

        return $this->created('user created');
    }
}