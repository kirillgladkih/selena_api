<?php

namespace Selena\Resources\Front\Queries;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Список направлений туров (круизов, маршрутов)
 * 
 * URL: https://selena-online.ru/rest/v1/tourdirectionlist/<int:dirid - ID направления туризма>
 * 
 * Параметры URL:
 * 
 * <int:tourdirid - ID направления туров>
 */
class TourDirectionListQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/tourdirectionlist";
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
    protected array $attributes = ["dirid", "tourdirid"];

    /**
     * @return void
     * @throws \Exception
     */
    protected function resolve(): void
    {
        if (!isset($this->params["dirid"])) throw new \Exception("dirid param is null");

        $this->url = $this->url . "/" . $this->params["dirid"];

        $this->setParamsInUrlQuery(["tourdirid"]);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function send(ClientInterface $client): ResponseInterface
    {
        return $client->sendRequest($this->request);
    }
}
