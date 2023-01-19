<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список рейсов (перевозка в рамках тура)
 * 
 * URL: https://selena-online.ru/rest/v1/triplist/<int:tourid - ID тура>
 */
class TripListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/triplist";
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
    protected array $attributes = ["tourid"];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["tourid"])) throw new \Exception("tourid param is null");

        $this->url = $this->url . "/" . $this->params["tourid"];
        
        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
