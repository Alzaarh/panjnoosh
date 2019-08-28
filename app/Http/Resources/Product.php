<?php

namespace App\Http\Resources;

use App\Http\Resources\Category as CategoryResource;
use Illuminate\Http\Resources\Json\Resource;

class Product extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'shortDescription' => $this->short_description,

            'description' => $this->description,

            'thumbnail' => $this->logo,

            'price' => $this->price,

            'pictures' => \App\Http\Resources\ProductPicture::collection($this->pictures),

            'quantity' => $this->quantity,

            'off' => $this->off,

            'category' => new CategoryResource($this->category),

            'active' => $this->active,

            'views' => $this->when($this->views, $this->views),
        ];
    }
}
