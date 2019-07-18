<?php

namespace App\Traits;

trait ResponseTrait
{
    //
    // ─── 200 RESPONSE ───────────────────────────────────────────────────────────────
    //

        public function ok($data)
        {
            return response()->json(['data' => $data], 200);
        }
    
    //
    // ─── 201 RESPONSE ───────────────────────────────────────────────────────────────
    //

        public function created($data)
        {
            return response()->json(['data' => $data], 201);
        }
} 
