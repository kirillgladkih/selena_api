<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список направлений туризма
 * 
 * URL: https://selena-online.ru/rest/v1/directionlist/<int:directionid - ID направления>
 */
class DirectionListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/directionlist";
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
    protected array $attributes = [
        "directionid"
    ];

    protected function resolve(): void
    {
        if (!isset($this->params["directionid"])) throw new \Exception("directionid param is null");

        $this->url = $this->url . "/" . $this->params["directionid"];
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
