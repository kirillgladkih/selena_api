<?php

namespace Selena\Tasks\Tours;

use Psr\Http\Client\ClientInterface;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

class GetOffersForTours implements TaskContract
{

    /**
     * @var int
     */
    protected int $object_id;

    /**
     * @var array
     */
    protected array $tour_ids;

    /**
     * @param int $object_id
     * @param array $tour_ids
     */
    public function __construct(int $object_id, array $tour_ids)
    {
        $this->object_id = $object_id;

        $this->tour_ids = $tour_ids;
    }

    /**
     * @return array|null
     */
    public function get(): ?array
    {
        /**
         * @var FrontApiCacheRepository $cacheFrontApiRepository
         */
        $cacheFrontApiRepository = SelenaService::instance()->get(FrontApiCacheRepository::class);

        $offers = $cacheFrontApiRepository->offers($this->object_id);

        foreach ($offers as $offer) {

            if (in_array($offer["tourid"], $this->tour_ids)) {

                $result[$offer["tourid"]]["tourid"] = $offer["tourid"];

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
    }
}
