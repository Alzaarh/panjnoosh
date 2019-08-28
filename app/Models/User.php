<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model {

    use SoftDeletes;
    
    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'role'
    ];
    protected $hidden = [
        'password',
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function addresses()
    {
        return $this->hasMany(\App\Models\UserAddress::class);
    }

    public function findAddressById($id) {
        $userAddress = $this->addresses()->where('id', $id)->first();

        if (!$userAddress) {
            throw new ModelNotFoundException();
        }

        return $userAddress;
    }
}
