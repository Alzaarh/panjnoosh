<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPicture extends Model 
{
    protected $fillable = [
        'path',
        'product_id'
    ];

    public $timestamps = false;
}
