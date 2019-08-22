<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model {
    
    public function category() {
        
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function discount() {
        
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
}
