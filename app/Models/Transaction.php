<?php

namespace App\Models;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    protected $fillable = [
        'transaction_code',
        'user_id',
        'order_id',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
