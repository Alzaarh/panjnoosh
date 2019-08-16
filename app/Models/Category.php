<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    //Maximum number of resource returned for pagination
    public static $maxPaginate = 50;
    //One to many relation with Product
    public function products() {
        return $this->hasMany(\App\Models\Product::class);
    }
}
