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
     * @param array $args
     * @return mixed
     */
    public function handle(string $class, array $args, ?callable $exceptionControl = null)
    {
        try {

            $process = $this->createProcess($class, ...$args);

            return $process();

        } catch (\Throwable $throwable) {

            if(!is_null($exceptionControl)){

                $exceptionControl($throwable);

            }

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
