<?php

namespace Selena\Reports;

use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

class ApiRequestReport implements ReportContract
{

    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * @var BasicQuery
     */
    protected BasicQuery $query;

    /**
     * @param ResponseInterface $response
     * @param BasicQuery $query
     */
    public function __construct(ResponseInterface $response, BasicQuery $query)
    {
        $this->response = $response;

        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $payload = $this->query->__toString();

        $payload .= "status: " . $this->response->getStatusCode() . PHP_EOL;

        $payload .= "reason: " . $this->response->getReasonPhrase() . PHP_EOL;

        $payload .= "Time: " . date("Y-m-d H:i:s");

        return $payload;
    }
}