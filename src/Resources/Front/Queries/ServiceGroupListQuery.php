<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientExceptionInterface;
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
     * @return void
     * @throws \Exception
     */
    protected function resolve(): void
    {
        if (!isset($this->params["objectid"])) throw new \Exception("objectid param is null");

        $this->url = $this->url . "/" . $this->params["objectid"];

        $this->setParamsInUrlQuery(["tourid", "apartmentid", "servicegroupid"]);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
