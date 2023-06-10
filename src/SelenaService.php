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
        $cacheConfig = [
            "apartmentList" => 60 * 60 * 24,
            "roomList" => 60 * 60 * 24,
            "apartmentPrice" => 60 * 60 * 24,
            "offers" => 60 * 60,
            "tourList" => 60 * 60 * 24,
            "tourStandList" => 60 * 60 * 24,
            "serviceList" => 60 * 60 * 24,
            "tourPackList" => 60 * 60 * 24,
            "unitList" => 60 * 60 * 24
        ];

        $clients = [
            new Client(["auth" => ["login", "password"]]),
        ];

        $frontApi = new FrontApi($clients);

        $bookingApi = new BookingApi($clients);

        $cachePool = new FilesystemAdapter("selena_cache", 0, __DIR__ . "/../../cache");

        $logger = new FileLogger(__DIR__ . "/../logs");

        $this->set(LoggerInterface::class, $logger);

        $this->set(FrontApi::class, $frontApi);

        $this->set(BookingApi::class, $bookingApi);

        $this->set(TaskHandler::class, new TaskHandler());

        $frontApiCacheRepository = new FrontApiCacheRepository($cachePool);

        $frontApiCacheRepository->loadCacheLifetimes($cacheConfig);

        $this->set(FrontApiCacheRepository::class, $frontApiCacheRepository);
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