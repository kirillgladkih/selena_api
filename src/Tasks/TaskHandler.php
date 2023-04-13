<?php

namespace Selena\Tasks;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class TaskHandler
{
    /**
     * Client
     *
     * @var ClientInterface
     */
    protected ClientInterface $client;
    /**
     * Cache pool
     *
     * @var FilesystemAdapter
     */
    protected FilesystemAdapter $cachePool;
    /**
     * Init
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;

        $this->cachePool = new FilesystemAdapter("task_cache", 0, __DIR__ . "/../../cache");
    }
    /**
     * Resolve task
     *
     * @param TaskContract $task
     * @return mixed
     */
    public function handleWithoutCache(TaskContract $task)
    {
        $callable = $task->get();

        return $callable($this->client);
    }
    /**
     * Handle task with cache
     *
     * @param TaskContract $task 
     * @param int $cache_lifetime = 3600
     * @return mixed
     */
    public function handleWithCache(TaskContract $task, int $cache_lifetime = 3600)
    {
        $result = $this->cachePool->get($task->tag(), function(ItemInterface $item) use ($cache_lifetime, $task){
            
            $item->expiresAfter($cache_lifetime);

            return $this->handleWithoutCache($task);

        });

        return $result;
    }
}
