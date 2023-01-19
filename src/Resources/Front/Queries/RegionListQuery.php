<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список регионов
 * 
 * URL: https://selena-online.ru/rest/v1/regionlist/<int:dirid - ID направления туризма>
 */
class RegionListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/regionlist";
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
        "dirid"
    ];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["dirid"])) throw new \Exception("dirid param is null");

        $this->url = $this->url . "/" . $this->params["dirid"];

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
