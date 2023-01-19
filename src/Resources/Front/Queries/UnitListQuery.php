<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список корпусов (палуб, для экскурсионных туров - гостиниц)
 * 
 * URL: https://selena-online.ru/rest/v1/unitlist/<int:objectid - ID объекта размещения>
 * 
 * Параметры URL:
 * 
 * <int:unitid - ID корпуса (палубы, гостиницы)>
 */
class UnitListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/unitlist";
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
    protected array $attributes = ["objectid", "unitid"];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["objectid"])) throw new \Exception("objectid param is null");

        $this->url = $this->url . "/" . $this->params["objectid"];

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
