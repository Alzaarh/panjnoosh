<?php

namespace App\Http\Controllers;

use App\Imports\StateImport;
use App\Models\State;
use App\Http\Resources\State as StateResource;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StatesController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['only' => 'create', 'update', 'delete']);

        $this->middleware('admin', ['only' => 'create', 'update', 'delete']);
    }

    public function index() {
        return StateResource::collection(State::all());
    }

    public function show($id) {
        $state = State::findOrFail($id);

        $state->cities;

        return new StateResource($state);
    }

    public function create(Request $request) {
        $this->validateState($request);

        if ($request->input('title')) {
            $state = State::create(['title' => $request->input('title')]);

            return response()->json(['data' => $state], 201);
        }

        if ($request->file('states')->isValid()) {
            if ($request->file('states')->getClientOriginalExtension() !== 'xlsx') {
                return response()->json(['message' => 'bad file extension'], 400);
            }

            Excel::import(new StateImport, $request->file('states'));

            return response()->json(['message' => 'created'], 201);
        }
        
        return response()->json(['message' => 'bad file upload'], 400);
    }

    public function update(Request $request, $id) {
        $state = State::findOrFail($id);

        $this->validateState($request);

        $state->update(['title' => $request->input('title')]);

        return response()->json(['data' => $state], 200);
    }

    public function delete($id) {
        $state = State::findOrFail($id);

        $state->delete();

        return response()->json(['message' => 'state deleted']);
    }

    private function validateState($request) {
        $this->validate($request, [
            'title' => 'string'
        ]);
    }
}
