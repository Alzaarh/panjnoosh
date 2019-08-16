<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Redis;

class IncrementCategoryViewCount extends Job {
    private $redisKey;
    private $categoryID;
    public function __construct($categoryID, $redisKey) {
        $this->categoryID = $categoryID;
        $this->redisKey = $redisKey;
    }
    public function handle() {
        Redis::ZINCRBY($this->redisKey, 1, $this->categoryID);
    }
}