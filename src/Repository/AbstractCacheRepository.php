<?php

namespace Selena\Repository;

use Symfony\Component\Cache\Adapter\AbstractAdapter;

abstract class AbstractCacheRepository
{
    /**
     * @var array
     */
    protected array $cacheLifetimes = [];

    /**
     * @var AbstractAdapter
     */
    protected AbstractAdapter $cachePool;

    /**
     * @param AbstractAdapter $adapter
     */
    public function __construct(AbstractAdapter $adapter)
    {
        $this->cachePool = $adapter;
    }

    /**
     * @param array $cacheLifetimes
     * @return void
     */
    public function loadCacheLifetimes(array $cacheLifetimes)
    {
        $this->cacheLifetimes = $cacheLifetimes;
    }
}
