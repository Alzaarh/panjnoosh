<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Request;

class Category extends Resource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'views' => $this->when($this->view_count, $this->view_count),
            'createdAt' => $this->when(isset(Request::instance()->user) && 
                Request::instance()->user->role == 'admin', $this->created_at),
            'updatedAt' => $this->when(isset(Request::instance()->user) && 
            Request::instance()->user->role == 'admin', $this->updated_at),
        ];
    }
}
