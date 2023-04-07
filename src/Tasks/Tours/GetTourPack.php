<?php

namespace Selena\Tasks\Tours;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\Subtasks\GetDiscountsForObject;
use Selena\Tasks\Subtasks\GetMinPriceForTour;
use Selena\Tasks\Subtasks\GetOffersForTour;
use Selena\Tasks\TaskContract;

/**
 * Получить cписок видов путёвок
 */
class GetTourPack implements TaskContract
{
    /**
     * ID объекта размещения
     *
     * @var integer
     */
    protected int $objectid;
    /**
     * Init
     *
     * @param integer $objectid
     * @param integer $tourid
     */
    public function __construct(int $objectid)
    {
        $this->objectid = $objectid;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        $class = str_replace('\\', '_', self::class);

        return $class . "_{$this->objectid}";
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

                $result = $frontApi->tourPackList(["objectid" => $this->objectid])["tourpacks"] ?? null;
            
            } catch (ApiException $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
