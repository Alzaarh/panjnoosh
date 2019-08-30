<?php

namespace App\Http\Controllers;

use App\Http\Resources\City as CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['only' => ['create', 'update', 'delete']]);

        $this->middleware('admin', ['only' => ['create', 'update', 'delete']]);
    }

    public function index(Request $request) {
        return CityResource::collection(City::where('title', 'like', '%' . $request->query('search') . '%'));
    }

    public function show($id) {
        $city = City::findOrFail($id);

        $city->state;

        return new CityResource($city);
    }

    public function create(Request $request) {
        
    }

    private function validateSearchTerm($request) {
        $this->validate($request, [
            'search' => 'required|string|max:30'
        ]);
    }
}
