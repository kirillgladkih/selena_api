<?php

namespace Selena\Reports;

use Selena\Exceptions\ApiException;
use Throwable;

class DefaultReport implements ReportContract
{
    /**
     * @var Throwable
     */
    protected Throwable $throwable;

    /**
     * @param Throwable $throwable
     */
    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $payload = "Trace: " . PHP_EOL . $this->throwable->getTraceAsString() . PHP_EOL;

        $payload .= "Error: " . $this->throwable->getMessage() . PHP_EOL;

        if ($this->throwable instanceof ApiException) {

            $payload .= $this->throwable->getQuery() . PHP_EOL;

        }

        $payload .= "Time: " . date("Y-m-d H:i:s");

        return $payload;
    }
}