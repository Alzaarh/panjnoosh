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

    protected $casts = [
        'status' => 'string',
    ];

    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function products()
    {
        return $this->belongsToMany(\App\Models\Product::class)
            ->withPivot('product_title', 'product_price', 'quantity');
    }
}
