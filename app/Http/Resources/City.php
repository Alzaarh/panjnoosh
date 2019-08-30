<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class City extends Resource {
    public function toArray($request) {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'state' => new \App\Http\Resources\State($this->whenLoaded('state'))
        ];
    }
}
