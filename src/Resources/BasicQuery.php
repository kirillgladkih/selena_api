<?php

namespace Selena\Resources;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BasicQuery
{

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

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

        $this->resolve();

        $this->request = new Request($this->method, $this->url, $this->headers, $this->body);
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * To string magic method
     *
     * @return string
     */
    public function __toString()
    {
        $string  = "url: " . $this->url . " method: " . $this->method .  PHP_EOL;

        if (!empty($this->params)) {

            $string .= "params: ";

            foreach ($this->params as $key => $value){

                if(!is_string($value)) $value = json_encode($value ?? [], JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

                $string .= PHP_EOL . " - $key: $value";

            } 

        }

        return $string;
    }

    /**
     * Resolve params
     *
     * @param array $data
     * @return void
     */
    protected function resolveParams(array $data)
    {
        foreach ($this->attributes as $attribute){

            if (($param = $data[$attribute] ?? null) && !isset($this->params[$attribute])){

                $this->params[$attribute] = $param;

            }
        }
    }

    /**
     * Set params in query url
     *
     * @param array<string> $attributes
     * @return void
     */
    protected function setParamsInUrlQuery(array $attributes)
    {
        $keys = array_intersect($attributes, $this->attributes);

        if (count($keys) > 0) $this->url = $this->url . "?";

        foreach ($keys as $key){
            if ($value = $this->params[$key] ?? null){
                $this->url .= "$key=$value&";
            }
        }
    }

    /**
     * @return void
     */
    abstract protected function resolve(): void;

    /**
     * @param ClientInterface $client
     * @return ResponseInterface
     */
    abstract public function send(ClientInterface $client): ResponseInterface;
}
