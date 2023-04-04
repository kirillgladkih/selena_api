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
 * Получить тур детально
 */
class GetTourDetail implements TaskContract
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
    public function tag()
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

                $tour = $frontApi->tourList(["objectid" => $this->objectid, "tourid" => $this->tourid])["tours"][0] ?? [];

                $offersForTourTask = new GetOffersForTour($this->objectid, $this->tourid);

                $minPriceForTourTask = new GetMinPriceForTour($this->objectid, $this->tourid);

                $discountsForObjectTask = new GetDiscountsForObject($this->objectid);

                $result = [
                    "tour" => $tour,
                    "amount_places" => ($offersForTourTask->get())($client),
                    "min_price" => ($minPriceForTourTask->get())($client),
                    "discounts" => ($discountsForObjectTask->get())($client)
                ];
            } catch (ApiException $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
