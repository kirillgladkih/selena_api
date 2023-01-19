<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список видов путёвок
 * 
 * URL: https://selena-online.ru/rest/v1/tourpacklist/<int:objectid - ID объекта размещения>
 * 
 * Параметры URL:
 * 
 * <int:tourpackid - ID вида путёвок>
 */
class TourPackListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/tourpacklist";
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
    protected array $attributes = ["objectid", "tourpackid"];
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

        $this->setParamsInUrlQuery(["tourpackid"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
