<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список туров (круизов, маршрутов)
 * 
 * URL: https://selena-online.ru/rest/v1/tourlist/<int:objectid - ID объекта размещения>
 * 
 * Параметры URL:
 * 
 * <int:tourid - ID тура>
 * 
 * <int:tdesc - флаг "включить график тура">
 * 
 * <date(yyyy-mm-dd):from - начальная дата границы поиска>
 * 
 * <date(yyyy-mm-dd):to - конечная дата границы поиска>
 * 
 * <int:fromcityid - ID города, с которого начинается тур>
 * 
 * <int:tocityid - ID города, в котором заканчивается тур>
 * 
 * <int:stopcityid - ID города, который встречается на маршруте тура либо которым тур заканчивается>
 */
class TourListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/tourlist";
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
        "tdesc",
        "from",
        "to",
        "fromcityid",
        "tocityid",
        "stopcityid"
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

        $this->setParamsInUrlQuery([
            "tourid",
            "tdesc",
            "from",
            "to",
            "fromcityid",
            "tocityid",
            "stopcityid"
        ]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
