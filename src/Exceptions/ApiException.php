<?php

namespace Selena\Exceptions;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class ApiException extends RuntimeException
{
    protected $response;

    protected $query;

    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

     public function getResponse()
    {
        return $this->response;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
