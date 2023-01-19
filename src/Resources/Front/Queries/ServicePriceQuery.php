<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Цены на услуги
 * 
 * URL: https://selena-online.ru/rest/v1/serviceprice/<int:serviceid - ID услуги> 
 * 
 * Параметры URL:
 * 
 * <int:tourid - ID тура>
 * 
 * <int:priceid - ID строки прайса>
 * 
 * <date(yyyy-mm-dd):begindate - дата актуальности>
 */
class ServicePriceQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/serviceprice";
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
        "serviceid",
        "tourid",
        "priceid",
        "begindate"
    ];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["serviceid"])) throw new \Exception("serviceid param is null");

        $this->url = $this->url . "/" . $this->params["serviceid"];

        $this->setParamsInUrlQuery([ "tourid","priceid","begindate"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
