<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'short_desc',
        'desc',
        'price',
        'off',
        'quantity',
        'thumbnail',
        'category_id',
    ];
}
