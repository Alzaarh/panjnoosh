<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model {
    //number of users returned when no pagination is present
    public static $number = 50;
    protected $fillable = [
        'username',
        'password',
    ];
    protected $hidden = [
        'password',
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
