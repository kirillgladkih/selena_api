<?php

namespace Selena\Tasks;

use Closure;
use Selena\Logger\LoggerInterface;
use Selena\Reports\DefaultReport;
use Selena\SelenaService;

class TaskHandler
{
    /**
     * @param string $class
     * @param ...$args
     * @return mixed
     */
    public function handle(string $class, ...$args)
    {
        try {

            $process = $this->createProcess($class, ...$args);

            return $process();

        } catch (\Throwable $throwable) {

            $report = new DefaultReport($throwable);

            /**
             * @var LoggerInterface $logger
             */

            $logger = SelenaService::instance()->get(LoggerInterface::class);

            $logger->error($report);

            return null;
        }
    }


    /**
     * @param string $class
     * @param ...$args
     * @return Promise
     */
    public function promise(string $class, ...$args): Promise
    {
        return new Promise($this->createProcess($class, ...$args));
    }

    /**
     * @param string $class
     * @param ...$args
     * @return Closure
     */
    protected function createProcess(string $class, ...$args): Closure
    {
        return function () use ($class, $args) {

            $class = new $class(...$args);

            return $class->get();

        };
    }
}
