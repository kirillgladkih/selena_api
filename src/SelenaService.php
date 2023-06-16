<?php

namespace Selena;

use GuzzleHttp\Client;
use Selena\Logger\FileLogger;
use Selena\Logger\LoggerInterface;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Booking\BookingApi;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskHandler;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class SelenaService extends Container
{

    /**
     * @var SelenaService
     */
    protected static self $instance;

    /**
     * Bootstrap
     *
     * @return void
     */
    protected function boot()
    {
    }

    /**
     * @return self
     */
    public static function instance(): self
    {
        if (!isset(self::$instance)) {

            self::$instance = new self();

            self::$instance->boot();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }
}