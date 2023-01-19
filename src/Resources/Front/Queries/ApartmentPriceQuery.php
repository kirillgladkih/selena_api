<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Цены на размещение (проживание)
 * 
 * URL: https://selena-online.ru/rest/v1/apartmentprice/<int:apartmentid - ID категории номера (каюты)>
 * 
 * Параметры URL:
 * 
 * <int:objectid - ID объекта размещения>
 *
 * <int:tourid - ID тура>
 *
 * <int:tourpackid - ID вида путёвки>
 *
 * <int:duration - продолжительность тура>
 *
 * <int:priceid - ID строки прайса>
 *
 * <date(yyyy-mm-dd):begindate - дата тура>
 */
class ApartmentPriceQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/apartmentprice";
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
        "apartmentid",
        "objectid",
        "tourid",
        "tourpackid",
        "duration",
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
        if (!isset($this->params["apartmentid"])) throw new \Exception("apartmentid param is null");

        $this->url = $this->url . "/" . $this->params["apartmentid"];

        $this->setParamsInUrlQuery(["objectid","tourid","tourpackid","duration","priceid","begindate"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
