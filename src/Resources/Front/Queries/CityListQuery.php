<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список городов и административных муниципальных образований
 * 
 * URL: https://selena-online.ru/rest/v1/citylist/<int:cityid - ID города>
 * 
 * Параметры URL:
 * 
 * <string:fias_guid - уникальный идентификатор в российском государственном реестре адресов (ФИАС)>
 * 
 * <string:name - имя объекта справочника. Поиск выполняется по вхождению name в имя>
 */
class CityListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/citylist";
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
    protected array $attributes = ["cityid", "fias_guid", "name"];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["cityid"])) throw new \Exception("cityid param is null");

        $this->url = $this->url . "/" . $this->params["cityid"];

        $this->setParamsInUrlQuery(["name", "fias_guid"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
