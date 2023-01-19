<?php

namespace Selena\Resources;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BasicApi
{
   /**
     * Client
     *
     * @var ClientInterface
     */
    protected ClientInterface $client;
    /**
     * Init
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
}
