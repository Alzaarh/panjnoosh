<?php

namespace App\Http\Resources;

use App\Http\Resources\User;
use Illuminate\Http\Resources\Json\Resource;

class Blog extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'user' => new User($this->user),
            'created_at' => $this->created_at,
            'update_at' => $this->updated_at,
        ];
    }
}
