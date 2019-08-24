<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Request;

class User extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'profilePic' => $this->profile_picture,
            'token' => $this->when($this->token, $this->token),
            'role' => $this->when(isset(Request::instance()->user) &&
                Request::instance()->user->role == 'admin', $this->role),
            'createdAt' => $this->when(isset(Request::instance()->user) &&
                Request::instance()->user->role == 'admin', $this->created_at),
            'updatedAt' => $this->when(isset(Request::instance()->user) &&
                Request::instance()->user->role == 'admin', $this->updated_at),
        ];
    }
}
