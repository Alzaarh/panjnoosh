<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'short_description',
        'description',
        'price',
        'quantity',
        'active',
        'off',
        'category_id'
    ];

    public $casts = [
        'active' => 'boolean',
    ];
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
    public function discounts()
    {
        return $this->hasMany(\App\Models\Discount::class)
            ->where(function ($query) {

                $query->where('starting_at', '<=', Carbon::now()->toDateString())
                    ->orWhere('starting_at', null);
            })
            ->where(function ($query) {
                $query->where('ending_at', '>=', Carbon::now()->toDateString())
                    ->orWhere('ending_at', null);
            });
    }
    public function pictures()
    {
        return $this->hasMany(\App\Models\ProductPicture::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', true);
        });
    }
}
