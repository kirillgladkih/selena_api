<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список объектов размещения 
 * 
 * URL: https://selena-online.ru/rest/v1/objectlist/<int:regionid - ID региона> 
 * 
 * Параметры URL:
 * 
 * <int:objectid - ID объекта размещения>
 */
class ObjectListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/objectlist";
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
        "regionid",
        "objectid"
    ];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["regionid"])) throw new \Exception("regionid param is null");

        $this->url = $this->url . "/" . $this->params["regionid"];

        $this->setParamsInUrlQuery(["objectid"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
