<?php

namespace Selena\Tasks\Rooms;

use Psr\Http\Client\ClientInterface;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 *  Получить список комнат с свободными местами
 */

class GetRoomsWithOffers implements TaskContract
{
    /**
     * Apartment id
     *
     * @var integer
     */
    protected int $apartmentid;
    /**
     * Tour id
     *
     * @var integer
     */
    protected int $tourid;
    /**
     * Object id
     *
     * @var integer
     */
    protected int $objectid;
    /*
     * Init
     *
     */
    public function __construct(int $objectid, int $tourid, int $apartmentid)
    {
        $this->tourid = $tourid;

        $this->apartmentid = $apartmentid;

        $this->objectid = $objectid;
    }
    /*
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        $class = str_replace('\\', '_', self::class);

        return $class . "_" . http_build_query(["tourid" => $this->tourid, "apartmentid" => $this->apartmentid]);
    }
    /*
     * Get callable
     *
     * @return callable
     */
    public function get()
    {
        return function (ClientInterface $client) {

            try {

                $frontApi = new FrontApi($client);

                $rooms = $frontApi->roomList(["apartmentid" => $this->apartmentid])["rooms"] ?? [];

                $offerRooms = $frontApi->offers([
                    "tourid" => $this->tourid, "apartmentid" => $this->apartmentid, "objectid" => $this->objectid
                ])["offers"][0]["rooms"] ?? [];

                $offerRooms = array_filter($offerRooms, fn ($item) => !empty($item));

                foreach ($rooms as $room) {

                    $offerRoom = array_values(
                        array_filter(
                            $offerRooms,
                            fn ($item) => $item["roomid"] == $room["id"]
                        )
                    );

                    $room["amount"] = $offerRoom[0]["amount"] ?? 0;

                    $result[] = $room;
                }

            } catch (\Exception $exception) {

                $result = null;
            }

            return $result ?? null;
        };
    }
}
