<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function products() 
    {
        return $this->belongsToMany(\App\Models\Product::class);
    }
}
