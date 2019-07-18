<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisTrait
{
    public function setExists($key)
    {
        $data = [];

        $result = Redis::SMEMBERS($key);

        foreach($result as $item)
        {
            array_push($data, json_decode($item));
        }

        return $data;
    }

    public function createSet($key, $data)
    {
        $count = 0;

        foreach($data as $item)
        {
            $count += Redis::SADD($key, json_encode($item));
        }

        return $count;
    }

    public function hashExists($key)
    {
        $result = Redis::HGETALL($key);

        $temp = [];

        foreach($result as $key => $value)
        {
            $temp[$key] = $value;
        }
        
        return $temp;
    }

    public function createHash($key, $data)
    {
        return Redis::HMSET($key, $data->toArray());
    }

    public function sSetIncr($key, $member)
    {
        return Redis::ZINCRBY($key, 1, $member);
    }
}