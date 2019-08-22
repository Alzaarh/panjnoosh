<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider {
    
    public function register() {
        
    }

    public function boot() {

        Carbon::serializeUsing(function ($carbon) {

            return $carbon->toDateTimeString();
        });
    }
}
