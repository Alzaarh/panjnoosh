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

    public function methodNotAllowed($data = 'method not allowed')
    {
        return response()->json(['data' => $data], 405);
    }

    public function badRequest($data)
    {
        return response()->json(['data' => $data], 400);
    }

    public function unAuth($data = 'not authorized')
    {
        return response()->json(['data' => $data], 401);
    }
} 
