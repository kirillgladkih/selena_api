<?php

namespace Selena\Resources;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Selena\Exceptions\ApiException;

abstract class BasicApi
{
    /**
     * @var Client[]
     */
    protected array $clients = [];

    /**
     * @var int
     */
    protected int $repeatFailedAttempts = 3;

    /**
     * @var array|int[]
     */
    protected array $repeatStatuses = [429];

    /**
     * @param Client[] $clients
     */
    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    /**
     * Resolve method
     *
     * @param BasicQuery $query
     * @param \Closure|null $responder
     * @return mixed
     */
    protected function respond(BasicQuery $query, ?\Closure $responder = null)
    {
        foreach ($this->clients as $client){

            $attempts = $this->repeatFailedAttempts;

            while ($attempts > 0){

                $attempts = $attempts - 1;

                $response = $query->resolve($client);

                if(!in_array($response->getStatusCode(), $this->repeatStatuses)){

                    return !is_null($responder) ? $responder($response) : $this->defaultResponder($response, $query);

                }

                sleep(2);

            }
        }

        return null;
    }

    /**
     * Default responder
     *
     * @param ResponseInterface $response
     * @param BasicQuery $query
     * @return mixed
     */
    protected function defaultResponder(ResponseInterface $response, BasicQuery $query)
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
