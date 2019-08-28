<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddress as UserAddressResource;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        return UserAddressResource::collection($request->user->addresses()->paginate());
    }

    public function show(Request $request, $id) {
        $userAddress = $request->user->findAddressById($id);

        return new UserAddressResource($userAddress);
    }

    public function create(Request $request) {
        $input = $this->validateAddress($request);

        $input['user_id'] = $request->user->id;

        $userAddress = UserAddress::create($input);

        return (new UserAddressResource($userAddress))->response(201);
    }

    public function update(Request $request, $id) {
        $userAddress = $request->user->findAddressById($id);

        $input = $this->validateAddress($request);

        $userAddress->update($input);

        return new UserAddressResource($userAddress);
    }

    public function delete(Request $request, $id) {
        $userAddress = $request->user->findAddressById($id);

        $userAddress->delete();

        return response()->json(['message' => 'address deleted'], 200);
    }

    private function validateAddress($request) {
        return $this->validate($request, [

            'state' => 'nullable|string|max:255',

            'city' => 'nullable|string|max:255',

            'address' => 'required|string|max:10000',

            'zipcode' => 'required|string|numeric',

            'phone' => 'required|string|numeric',

            'default' => 'boolean'
        ], [
            'state.*' => 'استان نامعتبر',

            'city.*' => 'شهر نامعتبر',

            'address.*' => 'آدرس نامعتبر',

            'zipcode.*' => 'کد پستی نامعتبر',

            'phone.*' => 'تلفن نامعتبر',

            'default.*' => 'نامعتبر'
        ]);
    }
}
