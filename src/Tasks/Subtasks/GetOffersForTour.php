<?php

namespace Selena\Tasks\Subtasks;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/**
 * Получить свободные места для тура
 */
class GetOffersForTour implements TaskContract
{
    /**
     * ID объекта размещения
     *
     * @var integer
     */
    protected int $objectid;
    /**
     * ID тура
     *
     * @var integer
     */
    protected int $tourid;
    /**
     * Init
     *
     * @param integer $objectid
     * @param integer $tourid
     */
    public function __construct(int $objectid, int $tourid)
    {
        $this->objectid = $objectid;

        $this->tourid = $tourid;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag(): string
    {
        $class = str_replace('\\', '_', self::class);

        return $class . "_{$this->objectid}_{$this->tourid}";
    }
    /**
     * Get callable
     *
     * @return callable
     */
    public function get()
    {
        return function (ClientInterface $client) {

            try {

                $frontApi = new FrontApi($client);

                $offers = $frontApi->offers(["objectid" => $this->objectid, "tourid" => $this->tourid]);

                foreach ($offers["offers"] ?? [] as $offer) $result = (int) ($result ?? 0) + (int) $offer["amount"] ?? 0;
            
            } catch (ApiException $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
