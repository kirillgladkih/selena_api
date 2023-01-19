<?php

namespace Selena\Resources;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BasicApi
{
    /**
     * Client
     *
     * @var ClientInterface
     */
    protected ClientInterface $client;
    /**
     * Init
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
    /**
     * Resolve method
     *
     * @param BasicQuery $query
     * @param \Closure|null $responder
     * @return mixed
     */
    protected function respond(BasicQuery $query, ?\Closure $responder = null): mixed
    {
        $response = $query->resolve($this->client);

        return !is_null($responder) ? $responder($response) : $this->defaultResponder($response);
    }
    /**
     * Default responder
     *
     * @param [type] $response
     * @return mixed
     */
    protected function defaultResponder($response): mixed
    {
        return $response;
    }
}
