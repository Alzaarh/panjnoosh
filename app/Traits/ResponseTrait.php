<?php

namespace App\Traits;

trait ResponseTrait
{
    public function ok($data)
    {
        return response()->json(['data' => $data], 200);
    }

    public function created($data)
    {
        return response()->json(['data' => $data], 201);
    }
} 
