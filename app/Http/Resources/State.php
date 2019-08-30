<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class State extends Resource {
    public function toArray($request) {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'cities' => \App\Http\Resources\City::collection($this->whenLoaded('cities'))
        ];
    }
}
