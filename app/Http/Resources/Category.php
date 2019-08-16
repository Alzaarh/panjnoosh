<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Category extends Resource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'views' => $this->when($this->view_count, $this->view_count),
            'products' => $this->when($request->query('with_product'), $this->products),
        ];
    }
}
