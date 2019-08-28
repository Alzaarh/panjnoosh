<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddress as UserAddressResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        User::paginate();
    }

    public function show(Request $request)
    {
        return new UserResource($request->user);
    }

    public function update(Request $request)
    {

    }

    public function indexAddresses(Request $request)
    {
        return UserAddressResource::collection($request->user->addresses);
    }

    public function showAddress(Request $request, $id)
    {

    }
}
