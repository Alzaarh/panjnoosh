<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'username' => $this->username,

            'email' => $this->email,

            'token' => $this->token,
        ];
    }
}
