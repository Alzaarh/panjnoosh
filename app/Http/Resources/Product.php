<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Product extends Resource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'shortDescription' => $this->short_description,
            'description' => $this->description,
            'thumbnail' => $this->main_logo,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'categoryID' => $this->category_id,
        ];
    }
}
