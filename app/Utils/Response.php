<?php

namespace App\Utils;

trait Response
{
    public function unprocEntity($data = 'validation error')
    {
        return response()->json(['data' => $data], 422);
    }
}