<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'zipcode',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function setPasswordAttribute($plainPassword)
    {
        $this->attributes['password'] = Hash::make($plainPassword);
    }
}
