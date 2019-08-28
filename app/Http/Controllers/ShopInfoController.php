<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ShopInfoController extends Controller {
    
    public function getStates() {
        return response()->json(['data' => DB::table('states')->get()], 200);
    }

    public function getCities() {
        return response()->json(['data' => DB::table('cities')->get()], 200);
    }
}
