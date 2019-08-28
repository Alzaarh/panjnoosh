<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Purchase extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userID' => $this->user_id,
            'addressID' => $this->user_address_id,
            'totalPrice' => $this->total_price,
            'products' => \App\Http\Resources\Product::collection(
                $this->when($this->products, $this->products)),
        ];
    }
}
