<?php

namespace Selena\Resources\Booking\Queries;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Selena\Resources\BasicQuery;

/**
 * Бронирование
 * 
 * URL: https://selena-online.ru/rest/v1/reserve
 * 
 * Documentation: https://selena-online.ru/rest/bookingapi/reserve
 */
class ReserveQuery extends BasicQuery
{
    /**
     * Url method
     *
     * @var string
     */
    protected string $url = "https://selena-online.ru/rest/v1/reserve";
    /**
     * Method
     *
     * @var string
     */
    protected string $method = "PUT";
    /**
     * Attributes
     * 
     * @var array
     */
    protected array $attributes = ["commit", "order", "tourists"];
    /**
     * Init
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        
        $this->headers["content-type"] = "application/json";
    }
    /**
     * Resolve
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    public function resolve(ClientInterface $client): ResponseInterface
    {
        $this->resolveBody();

        $request = $this->resolveRequest();

        $response = $client->sendRequest($request);

        return $response;
    }   
    /**
     * Resolve body
     *
     * @return void
     */
    private function resolveBody(): void
    {
        $data = [
            "commit" => $this->params["commit"] ?? false, 
            "order" => $this->params["order"] ?? [], 
            "tourists" => $this->params["tourists"] ?? []
        ];

        $this->body = json_encode($data);
    }
}
