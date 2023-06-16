<?php

namespace Selena\Resources\Booking\Queries;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Бронирование
 *
 * URL: https://selena-online.ru/rest/v1/reserve
 *
 * Documentation: https://selena-online.ru/rest/bookingapi/reserve
 */
class ReserveQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/reserve";
    /**
     * Method
     *
     * @var string
     */
    protected string $method = "PUT";
    /**
     * Attributes
     *
     * @var array
     */
    protected array $attributes = ["commit", "order", "tourists"];

    /**
     * @return void
     * @throws Exception
     */
    protected function resolve(): void
    {
        $this->headers["content-type"] = "application/json";

        $data = [
            "order" => $this->params["order"] ?? [],
            "commit" => $this->params["commit"] ?? false,
            "tourists" => $this->params["tourists"] ?? []
        ];

        $this->body = json_encode($data);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
