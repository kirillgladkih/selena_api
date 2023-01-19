<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Программа тура
 * 
 * URL: https://selena-online.ru/rest/v1/tourstandlist/<int:tourid - ID тура>
 * 
 * Параметры URL:
 * 
 * <int:tourstandid - ID остановки.>
 */
class TourStandListQuery extends BasicQuery
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
    protected array $attributes = [
        "tourid",
        "tourstandid"
    ];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["tourid"])) throw new \Exception("tourid param is null");

        $this->url = $this->url . "/" . $this->params["tourid"];

        $this->setParamsInUrlQuery(["tourstandid"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
