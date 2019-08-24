<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductPicture extends Resource {

    public function toArray($request) {
        return [
            'id' => $this->id,

            'productID' => $this->product_id,

            'path' => $this->path,
        ];
    }
}
