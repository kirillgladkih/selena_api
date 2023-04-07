<?php

namespace Selena\Helpers;

use Selena\Tasks\TaskContract;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CacheHelper
{
    /**
     * Cache pool
     *
     * @var FilesystemAdapter
     */
    protected FilesystemAdapter $cachePool;
    /**
     * Init
     *
     */
    public function __construct()
    {
        $this->cachePool = new FilesystemAdapter("task_cache");
    }
    /**
     * Closure with cache
     *
     * @param string $tag
     * @param callable $closure
     * @param integer $cache_lifetime
     * @return mixed
     */
    public function withCache(string $tag, callable $closure, int $cache_lifetime = 3600)
    {
        $result = $this->cachePool->get($tag, function(ItemInterface $item) use ($cache_lifetime, $closure){
            
            $item->expiresAfter($cache_lifetime);

            return $closure();
        });

        return $result;
    }
}
