<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Category as CategoryResource;

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

            'category' => new CategoryResource($this->category),
        ];
    }
}
