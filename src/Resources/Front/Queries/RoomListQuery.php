<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список номеров (кают)
 * 
 * URL: https://selena-online.ru/rest/v1/roomlist/<int:apartmentid - ID категории номера (каюты)>
 * 
 * Параметры URL:
 * 
 * <int:roomid - ID номера (каюты)>
 */
class RoomListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/roomlist";
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
    protected array $attributes = ["apartmentid", "roomid"];
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        if (!isset($this->params["apartmentid"])) throw new \Exception("apartmentid param is null");

        $this->url = $this->url . "/" . $this->params["objectid"];

        $this->setParamsInUrlQuery(["roomid"]);

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }
}
