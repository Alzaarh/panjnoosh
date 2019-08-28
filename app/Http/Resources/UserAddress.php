<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserAddress extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'state' => $this->state,

            'city' => $this->city,

            'address' => $this->address,

            'zipcode' => $this->zipcode,

            'phone' => $this->phone,

            'default' => $this->default,

            'createdAt' => $this->created_at,

            'updatedAt' => $this->updated_at,
        ];
    }
}
