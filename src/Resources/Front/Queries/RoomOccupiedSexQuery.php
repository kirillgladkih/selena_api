<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Пол туристов в забронированных заказах
 * 
 * URL: https://selena-online.ru/rest/v1/roomoccupiedsex/<int:tourid - ID тура>
 * 
 * Параметры URL:
 * 
 * <int:roomid - ID номера (каюты)>
 */
class RoomOccupiedSexQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/roomoccupiedsex";
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
        "roomid"
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

        $this->setParamsInUrlQuery(["roomid"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
