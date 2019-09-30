<?php

namespace App\Http\Resources;

use App\Http\Resources\User;
use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Order;

class Transaction extends Resource
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
            'code' => $this->code,
            'user' => new User($this->user),
            'order' => new Order($this->order),
            'amount' => $this->amount,
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at,
        ];
    }
}
