<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS = [
        'pending' => '0',
        'preparation' => '1',
        'sent' => '2',
        'delivered' => '3',
    ];

    public function products()
    {
        return $this->belongsToMany(\App\Models\Product::class);
    }
}
