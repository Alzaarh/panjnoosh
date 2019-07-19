<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Redis;

class IncProductViewCountJob extends Job
{
    private const REDIS_KEY = 'products_view_count';
    
    private $member;

    public function __construct($member)
    {
        $this->member = $member;
    }

    public function handle()
    {
        Redis::ZINCRBY(self::REDIS_KEY, 1, $this->member);
    }
}