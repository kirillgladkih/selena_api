<?php

namespace Selena\Tasks\Rooms;

use Psr\Http\Client\ClientInterface;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

/*
 *  Получить список комнат с свободными местами
 */

class GetRoomsWithOffers implements TaskContract
{
    /**
     *
     * @var integer
     */
    protected int $apartment_id;

    /**
     *
     * @var integer
     */
    protected int $tour_id;

    /**
     *
     * @var integer
     */
    protected int $object_id;


    /**
     * @param int $object_id
     * @param int $tour_id
     * @param int $apartment_id
     */
    public function __construct(int $object_id, int $tour_id, int $apartment_id)
    {
        $this->apartment_id = $apartment_id;

        $this->object_id = $object_id;

        $this->tour_id = $tour_id;
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

        $rooms = $cacheFrontApiRepository->roomList($this->apartment_id);

        $offers = $cacheFrontApiRepository->offers($this->object_id, $this->tour_id);

        $roomIds = array_map(fn($item) => $item["id"], $rooms);

        $offers = array_filter($offers, fn($offer) => $offer["apartmentid"] == $this->apartment_id);

        foreach ($offers as $offer){

            foreach ($offer["rooms"] ?? [] as $offerRoom){

                if(in_array($offerRoom["roomid"], $roomIds)){

                    $offerRoom["id"] = $offerRoom["roomid"];

                    $result[] = $offerRoom;

                }

            }

        }

        return $result ?? null;
    }
}
