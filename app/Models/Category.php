<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'details',
    ];
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }
}
