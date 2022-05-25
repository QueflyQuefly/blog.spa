<?php

namespace App\Repository;

use Redis;

class RedisRepository {

    /** @var Redis $redis */
    private $redis;

    public function __construct(string $redisHost, int $redisPort)
    {
        $this->redis = new Redis();
        $this
            ->redis
            ->connect($redisHost, $redisPort);
    }

    public function set(string $key, mixed $value, int $ttl = 60) 
    {
        $this
            ->redis
            ->setex($key, $ttl, $value);

        return $this;
    }

    public function get(string $key)
    {
        return $this
            ->redis
            ->get($key);
    }
}