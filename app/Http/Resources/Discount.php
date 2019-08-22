<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Discount extends Resource {

    public function toArray($request) {

        return [

            'id' => $this->id,

            'productID' => new \App\Http\Resources\Product($this->whenLoaded('product')),

            'off' => $this->off,

            'startingAt' => $this->starting_at,

            'endingAt' => $this->ending_at,
        ];
    }
}
