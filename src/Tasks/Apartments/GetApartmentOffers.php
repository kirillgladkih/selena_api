<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Params\Apartment\MainApartmentParams;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 * Получение свободных мест для апартаментов
 */

class GetApartmentOffers implements TaskContract
{
    /**
     * Params
     *
     * @var MainApartmentParams
     */
    protected MainApartmentParams $params;
    /**
     * Apartments
     *
     * @var array
     */
    protected $apartments;
    /*
     * Init
     *
     */
    public function __construct(MainApartmentParams $params, $apartments)
    {
        $this->params = $params;

        $this->apartments = $apartments;
    }
    /*
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        $class = str_replace('\\', '_', self::class);

        return $class . "_" . http_build_query($this->params->toArray());
    }
    /*
     * Get callable
     *
     * @return callable
     */
    public function get()
    {
        return function (ClientInterface $client) {


            $frontApi = new FrontApi($client);

            foreach ($this->apartments ?? [] as $key => $item) {

                try {

                    $offersQuery = ["apartmentid" => $item["id"], "tourid" => $this->params->tourid, "objectid" => $this->params->objectid];

                    $offer = $frontApi->offers($offersQuery)["offers"][0] ?? [];

                    $amount = $offer["amount"] ?? null;

                    if (!is_null($amount)) {

                        $item = ["apartmentid" => $item["id"], "amount_places" => $amount];

                        $rooms = $offer["rooms"] ?? [];

                        foreach ($rooms as $room)
                            if (!empty($room)) $item["rooms"][] = $room;

                        $result[] = $item;
                    }
                    /**
                     * Exception
                     */
                } catch (ApiException $exception) {

                    continue;
                }
            }

            return $result ?? null;
        };
    }
}
