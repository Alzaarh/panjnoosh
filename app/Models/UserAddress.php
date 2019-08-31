<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model {
    public $casts = [
        'default' => 'boolean',
    ];

    protected $fillable = [
        'state',
        'city',
        'address',
        'zipcode',
        'phone',
        'receiver_name',
        'default',
        'user_id',
    ];
}
