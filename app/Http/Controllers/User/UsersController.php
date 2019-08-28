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
        $this->middleware('admin', ['only' => [
            'index',
            'show',
            'create',
            'update',
            'delete'
            ]
        ]);
    }

    public function index(Request $request) {
        $this->validateIndex($request);

        return UserResource::collection(User::orderBy($request->query('sortBy') ?? 'id', $request->query('sortOrder') ?? 'asc')
            ->paginate($request->query('number')));
    }

    public function show(Request $request, $id) {
        return new UserResource(User::findOrFail($id));
    }

    public function create(Request $request) {
        $input = $this->validateUser($request);

        return (new UserResource(User::create($input)))->response(201);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $this->validateUpdateUser($request);

        $user->role = $request->input('role');

        $user->save();

        return new UserResource($user);
    }

    public function delete($id) {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'user deleted'], 200);
    }

    public function showSelf(Request $request) {
        return new UserResource($request->user);
    }
    public function indexAddresses(Request $request)
    {
        return UserAddressResource::collection($request->user->addresses);
    }

    public function showAddress(Request $request, $id)
    {

    }

    private function validateIndex($request) {
        $this->validate($request, [
            'number' => 'string|numeric|between:1, 100',

            'sortBy' => 'string|in:name,email,username,createdAt',

            'sortOrder' => 'string|in:asc,desc'
        ], [
            'number.*' => 'عدد نامعتبر',

            'sortBy.*' => 'نامعتبر',

            'sortOrder.*' => 'نامعتبر' 
        ]);
    }

    private function validateUser($request) {
        return $this->validate($request, [
            'name' => 'string|max:255',

            'username' => 'required|string|max:255|unique:users,username',

            'email' => 'string|email||max:255|unique:users,email',

            'password' => 'required|string|min:5|max:255',

            'role' => 'string|in:user,admin'
        ], [
            'name.*' => 'نام نامعتبر',

            'username.unique' => 'نام کاربری ثبت شده',

            'username.*' => 'نام کاربری نامعتبر',

            'email.unique' => 'ایمیل ثبت شده',

            'email.*' => 'ایمیل نامعتبر',

            'password.*' => 'رمزعبور نامعتبر',

            'role.*' => 'نامعتبر'
        ]);
    }

    private function validateUpdateUser($request) {
        $this->validate($request, [
            'role' => 'string|in:user,admin'
        ], [
            'role.*' => 'نامعتبر'
        ]);
    }
}
