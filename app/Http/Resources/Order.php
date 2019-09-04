<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Order extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'total_price' => $this->total_price,

            'user_id' => $this->user_id,

            'user_city' => $this->user_city,

            'user_state' => $this->user_state,

            'user_address' => $this->user_address,

            'user_zipcode' => $this->user_zipcode,

            'user_phone' => $this->user_phone,

            'user_receiver_name' => $this->user_receiver_name,

            'status' => $this->status,

            'created_at' => $this->created_at
        ];
    }
}
