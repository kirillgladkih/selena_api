<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Наличие мест
 * 
 * URL: https://selena-online.ru/rest/v1/offers/<int:objectid - ID объекта размещения>
 * 
 * Параметры URL:
 * 
 * <int:unitid - ID корпуса (палубы)>
 * 
 * <int:apartmentid - ID категории номера (каюты)>
 * 
 * <int:tourid - ID тура>
 * 
 * <int:tourdirectionid - ID направления туров>
 * 
 * <int:duration - продолжительность тура>
 * 
 * <date(yyyy-mm-dd):from - начальная дата границы поиска, по умолчанию текущая дата>
 * 
 * <date(yyyy-mm-dd):to - конечная дата границы поиска, по умолчанию текущая дата + год>
 */
class OffersQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/offers";
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
        "unitid",
        "apartmentid",
        "tourid",
        "tourdirectionid",
        "duration",
        "from",
        "to"
    ];

    /**
     * @return void
     * @throws \Exception
     */
    protected function resolve(): void
    {
        if (!isset($this->params["objectid"])) throw new \Exception("objectid param is null");

        $this->url = $this->url . "/" . $this->params["objectid"];

        $this->setParamsInUrlQuery(["unitid", "apartmentid", "tourid", "tourdirectionid", "duration", "from", "to"]);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
