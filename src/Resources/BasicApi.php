<?php

namespace Selena\Resources;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Exceptions\ApiException;

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

        return !is_null($responder) ? $responder($response) : $this->defaultResponder($response, $query);
    }
    /**
     * Default responder
     *
     * @param ResponseInterface $response
     * @param BasicQuery $query
     * @return mixed
     */
    protected function defaultResponder(ResponseInterface $response, BasicQuery $query): mixed
    {
        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data["error"])) {

            $exception = new ApiException($data["error"]["message"] ?? "", $data["error"]["code"] ?? 500);

            $exception->setQuery($query);

            $exception->setResponse($response);

            throw $exception;
        }

        return $data;
    }
}
