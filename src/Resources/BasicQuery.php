<?php

namespace Selena\Resources;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BasicQuery
{
    /**
     * Method for query
     *
     * @var string
     */
    protected string $method = "GET";
    /**
     * Url for query
     *
     * @var string
     */
    protected string $url = "";
    /**
     * Headers
     *
     * @var array
     */
    protected array $headers = [];
    /**
     * Body
     *
     * @var mixed
     */
    protected $body = null;
    /**
     * Required attributes
     *
     * @var array
     */
    protected array $attributes = [];
    /**
     * Prepared data
     *
     * @var array
     */
    protected array $params = [];
    /**
     * Init
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {

        $this->resolveParams($data);
    }
    /**
     * Resolve params
     *
     * @param array $data
     * @return void
     */
    protected function resolveParams(array $data): void
    {
        foreach ($this->attributes as $attribute)
            if ($param = $data[$attribute] ?? null)
                $this->params[$attribute] = $param;
    }
    /**
     * Resolve request
     *
     * @return RequestInterface
     */
    protected function resolveRequest(): RequestInterface
    {
        $request = new Request($this->method, $this->url, $this->headers, $this->body);

        return $request;
    }
    /**
     * Set params in query url
     *
     * @param array<string> $attributes
     * @return void
     */
    protected function setParamsInUrlQuery(array $attributes): void
    {
        $keys = array_intersect($attributes, $this->attributes);

        if (count($keys) > 0) $this->url = $this->url . "?";

        foreach ($keys as $key)
            if ($value = $this->params[$key] ?? null)
                $this->url .= "$key=$value&";
    }
    /**
     * Resolve query
     *
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    abstract public function resolve(ClientInterface $client): ResponseInterface;
}
