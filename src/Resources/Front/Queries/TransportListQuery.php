<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список видов транспорта для перевозки в рамках тура
 * 
 * URL: https://selena-online.ru/rest/v1/transportlist
 * 
 * Параметры URL:
 * 
 * <int:id - ID вида транспорта. Опциональный>
 */
class TransportListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/transportlist";
    /**
     * Method
     *
     * @var string
     */
    protected string $method = "GET";
    /**
     * Attributes
     * 
     * @var array
     */
    protected array $attributes = ["id"];

    /**
     * @return void
     */
    protected function resolve(): void
    {
        $this->setParamsInUrlQuery(["id"]);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
