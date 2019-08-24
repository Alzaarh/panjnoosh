<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddress as UserAddressResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showUser(Request $request)
    {
        return new UserResource($request->user);
    }
    public function indexAddresses(Request $request)
    {
        return UserAddressResource::collection($request->user->addresses);
    }
}
