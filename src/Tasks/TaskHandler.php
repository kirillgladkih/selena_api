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
     * Log directory
     *
     * @var string
     */
    protected $logDirectory;
    /**
     * Init
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;

        $this->cachePool = new FilesystemAdapter("task_cache", 0, __DIR__ . "/../../cache");

        $this->logDirectory = __DIR__ . "/../../logs";

        if (!is_dir($this->logDirectory)) mkdir($this->logDirectory);
    }
    /**
     * Resolve task
     *
     * @param TaskContract $task
     * @return mixed
     */
    public function handleWithoutCache(TaskContract $task)
    {
        try {

            $callable = $task->get();
            
            $result = $callable($this->client);

        } catch (\Exception $exception) {
            
            $payload = "Task: " . get_class($task) . PHP_EOL;

            $payload .= "Error: " . $exception->getMessage() . PHP_EOL;

            $payload .= "Cache Tag: " . $task->tag() . PHP_EOL;

            $payload .= "Time: " . date("Y-m-d H:i:s");

            $this->log($payload, "errors");
        
        }

        return $result ?? null;
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
        $result = $this->cachePool->get($task->tag(), function (ItemInterface $item) use ($cache_lifetime, $task) {

            $item->expiresAfter($cache_lifetime);

            $data = $this->handleWithoutCache($task);

            return !empty($data) ? $data : null;
        });

        if (empty($result)) $this->cachePool->delete($task->tag());

        return $result;
    }
    /**
     * Log
     *
     * @param mixed $payload
     * @return void
     */
    private function log(mixed $payload, string $logDirectory)
    {
        $logDirectory = $this->logDirectory . "/" . $logDirectory;

        if(!is_dir($logDirectory)) mkdir($logDirectory);

        $file = $logDirectory . "/" . $this->timestamp() . ".txt";
        
        file_put_contents($file, PHP_EOL, FILE_APPEND | LOCK_EX);

        file_put_contents($file, $payload, FILE_APPEND | LOCK_EX);

        file_put_contents($file, PHP_EOL, FILE_APPEND | LOCK_EX);
    }    
    /**
     * Timestamps
     *
     * @return string
     */
    private function timestamp()
    {
        return date("Y-m-d");
    }
}
