<?php

namespace Selena\Resources\Booking\Queries;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Бронирование
 * 
 * URL: https://selena-online.ru/rest/v1/cancel-order/<string:id - Идентификатор заказа>
 * 
 * Documentation: https://selena-online.ru/rest/bookingapi/cancel-order
 */
class CancelOrderQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/cancel-order";
    /**
     * Method
     *
     * @var string
     */
    protected string $method = "DELETE";
    /**
     * Attributes
     * 
     * @var array
     */
    protected array $attributes = ["id"];

    /**
     * @return void
     * @throws \Exception
     */
    protected function resolve(): void
    {
        if (!isset($this->params["id"])) throw new \Exception("id param is null");

        $this->url = $this->url . "/" . $this->params["id"];
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
