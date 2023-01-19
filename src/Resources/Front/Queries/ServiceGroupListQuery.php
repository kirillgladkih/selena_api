<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список групп услуг
 * 
 * URL: https://selena-online.ru/rest/v1/servicegrouplist/<int:objectid - ID объекта размещения> 
 * 
 * Параметры URL:
 * 
 * <int:tourid - ID тура (маршрута, круиза)>
 * 
 * <int:apartmentid - ID категории номера (каюты)>
 * 
 * <int:servicegroupid - ID группы услуг>
 */
class ServiceGroupListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/servicegrouplist";
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
        "objectid",
        "tourid",
        "apartmentid",
        "servicegroupid"
    ];
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

        $this->setParamsInUrlQuery(["tourid", "apartmentid", "servicegroupid"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
