<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class IncProductViewCount extends Job
{
    private $productID;

    public function __construct($productID)
    {
        $this->productID = $productID;
    }

    public function handle()
    {
        Redis::PIPELINE(function ($redis) {
            $redis->ZINCRBY(Carbon::now()->toDateString(), 1, $this->productID);
            $redis->ZINCRBY(Carbon::now()->format('Y-m'), 1, $this->productID);
            $redis->ZINCRBY(Carbon::now()->format('Y'), 1, $this->productID);
        });
    }
}
