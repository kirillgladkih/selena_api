<?php

namespace Selena\Tasks\Tours;

use DateTime;
use Psr\Http\Client\ClientInterface;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/**
 * Получить список список мест для туров
 */
class GetOffersForTours implements TaskContract
{
    /**
     * ID объекта размещения
     *
     * @var integer
     */
    protected int $objectid;
    /**
     * tour ids
     *
     * @var array
     */
    protected array $tourIds;
    /**
     * Init
     *
     * @param integer $objectid
     * @param array $tourIds
     */
    public function __construct(int $objectid, array $tourIds)
    {
        $this->objectid = $objectid;

        $this->tourIds = $tourIds;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        $class = str_replace('\\', '_', self::class);

        $tourIds = implode("_", $this->tourIds);

        return $class . "_{$this->objectid}_{$tourIds}";
    }
    /**
     * Get callable
     *
     * @return callable
     */
    public function get()
    {
        return function (ClientInterface $client) {

            $frontApi = new FrontApi($client);

            $offers = $frontApi->offers(["objectid" => $this->objectid, "from" => date("Y-m-d")])["offers"] ?? [];

            foreach ($offers as $offer) {

                if (in_array($offer["tourid"], $this->tourIds)) {

                    $result[$offer["tourid"]]["tourid"] =  $offer["tourid"];

                    $rooms = $offer["rooms"];

                    $amount = $offer["amount"];

                    $rooms = array_filter($rooms, function ($item) {
                        return !empty($item);
                    });

                    $result[$offer["tourid"]]["amount_places"] =
                        ($result[$offer["tourid"]]["amount_places"] ?? 0) + $amount;

                    $result[$offer["tourid"]]["rooms"] =
                        ($result[$offer["tourid"]]["rooms"] ?? 0) + count($rooms);
                }
            }

            return $result ?? null;
        };
    }
}
