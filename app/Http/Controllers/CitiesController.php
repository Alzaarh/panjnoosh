<?php

namespace App\Http\Controllers;

use App\Imports\CityImport;
use App\Http\Resources\City as CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CitiesController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['only' => ['create', 'update', 'delete']]);

        $this->middleware('admin', ['only' => ['create', 'update', 'delete']]);
    }

    public function index(Request $request) {
        return CityResource::collection(City::where('title', 'like', '%' . $request->query('search') . '%')->get());
    }

    public function show($id) {
        $city = City::findOrFail($id);

        $city->state;

        return new CityResource($city);
    }

    public function create(Request $request) {
        $this->validateCreate($request);

        if ($request->input('title') && $request->input('stateId')) {
            $city = City::create([
                'title' => $request->input('title'),

                'state_id' => $request->input('stateId')
            ]);

            return (new CityResource($city))->response(201);
        }

        if ($request->file('cities')) {
            Excel::import(new CityImport, $request->file('cities'));

            return response()->json(['message' => 'cities created'], 201);
        }

        return response()->json(['message' => 'file upload error'], 400);
    }

    public function update(Request $request, $id) {
        $city = City::findOrFail($id);

        $this->validateUpdate($request);

        if ($request->input('title')) {
            $city->title = $request->input('title');
        }

        if ($request->input('stateId')) {
            $city->state_id = $request->input('stateId');
        }

        $city->save();

        return new CityResource($city);
    }

    public function delete(Request $request, $id) {
        $city = City::findOrFail($id);

        $city->delete();

        return response()->json(['message' => 'city deleted'], 200);
    }

    private function validateSearchTerm($request) {
        $this->validate($request, [
            'search' => 'required|string|max:30'
        ]);
    }

    private function validateCreate($request) {
        $this->validate($request, [
            'title' => 'required_without:cities|string|max:255',

            'stateId' => 'required_without:cities|numeric|exists:states,id',

            'cities' => ['file', 'max:5000', function ($attribute, $value, $fail) {
                if ($value->getClientOriginalExtension() !== 'xlsx') {
                    $fail($attribute . 'is invalid');
                }
            }]
        ]);
    }

    private function validateUpdate($request) {
        $this->validate($request, [
            'string|max:255',

            'stateId' => 'numeric|exists:states,id'
        ]);
    }
}
