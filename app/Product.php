<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
        'off' => 'integer',
        'category_id' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }
}
