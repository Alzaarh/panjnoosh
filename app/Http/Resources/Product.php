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
            'thumbnail' => $this->main_logo,
            'price' => $this->price,
            'discount' => empty($this->discount) ? 0 : new \App\Http\Resources\Discount($this->discount),
            'pictures' => \App\Http\Resources\ProductPicture::collection($this->pictures),
            'quantity' => $this->quantity,
            'category' => new CategoryResource($this->category),
            'active' => $this->active,
            'views' => $this->when($this->views, $this->views),
        ];
    }
}
