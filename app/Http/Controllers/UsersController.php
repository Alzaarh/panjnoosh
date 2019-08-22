<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Errors;
use App\Models\User;
use App\Http\Resources\User as UserResource;

class UsersController extends Controller {
    use Errors;
    public function index(Request $request) {
        if($request->query('paginate') == 1) {
            return UserResource::collection(User::paginate());
        }
        return UserResource::collection(User::get()->take(User::$number));
    }
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('is.admin', ['only' => [
            'create',
            'index',
        ]]);
    }
    //Admin create a user
    public function create(Request $request) {
        $validatedInput = $this->validateCreate($request);
        $userData = $this->transform($validatedInput);
        $user = User::create($userData);
        return (new UserResource($user))->response(201);
    }
    //Validate user input for creating user
    private function validateCreate(Request $request) {
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
    //Transform user input to proper database input
    private function transform(array $data) {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }
}
