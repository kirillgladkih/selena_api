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
    protected int $repeatFailedAttempts = 2;

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
        $status = false;

        foreach ($this->clients as $client) {

            $response = $this->pending($client, $query, function ($client, $query) use (&$status) {

                $attempts = $this->repeatFailedAttempts;

                while ($attempts > 0 || !$status) {

                    $attempts = $attempts - 1;

                    $response = $this->pending($client, $query);

                    echo 'attempt - ' . $attempts . PHP_EOL;

                    $status = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 300;

                }

            });

            if($status){

                break;

            }

        }

        $response = $response ?? null;

        return !is_null($responder) ? $responder($response) : $this->defaultResponder($response, $query);
    }

    /**
     * @param $client
     * @param $query
     * @param callable|null $exceptionHandler
     * @return ResponseInterface|null
     */
    protected function pending($client, $query, ?callable $exceptionHandler = null): ?ResponseInterface
    {
        try {

            $response = $query->resolve($client);

        } catch (\Exception $exception) {

            if (isset($exceptionHandler)) {

                $exceptionHandler($client, $query);

            }

            $response = null;

        }

        return $response;
    }

    /**
     * Default responder
     *
     * @param null|ResponseInterface $response
     * @param BasicQuery $query
     * @return mixed
     */
    protected function defaultResponder(?ResponseInterface $response, BasicQuery $query)
    {
        if (!$response) {

            return null;

        }

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
