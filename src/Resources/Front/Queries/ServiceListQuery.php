<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список услуг 
 * 
 * URL: https://selena-online.ru/rest/v1/servicelist/<int:objectid - ID объекта размещения> 
 * 
 * Параметры URL:
 * 
 * <int:tourid - ID тура (маршрута, круиза)>
 * 
 * <int:apartmentid - ID категории номера (каюты)>
 * 
 * <boolean:default - флаг "Услуга предлагается по умолчанию". Покажет только те услуги, которые должны входить в промо-цену тура>
 * 
 * <boolean:confirm_required - флаг "Услуга требует подтверждения менеджера">
 * 
 * <int:serviceid - ID услуги>
 */
class ServiceListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/servicelist";
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
        "default",
        "confirm_required",
        "serviceid"
    ];

    /**
     * @return void
     * @throws \Exception
     */
    protected function resolve(): void
    {
        if (!isset($this->params["objectid"])) throw new \Exception("objectid param is null");

        $this->url = $this->url . "/" . $this->params["objectid"];

        $this->setParamsInUrlQuery(["tourid", "apartmentid", "default", "confirm_required", "serviceid"]);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
