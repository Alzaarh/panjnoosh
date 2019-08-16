<?php

namespace App\Utils;

trait Response {
    
    public function unprocEntity($data = 'validation error')
    {
        return response()->json(['data' => $data], 422);
    }

    public function forbidden($data = 'access forbidden')
    {
        return response()->json(['data' => $data], 403);
    }
}