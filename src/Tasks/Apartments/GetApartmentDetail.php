<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Helpers\ApartmentHelper;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/**
 * Апартаметы детально
 */
class GetApartmentDetail implements TaskContract
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
     * ID апартаментов
     *
     * @var integer
     */
    protected int $apartmentid;
    /**
     * Init
     *
     * @param integer $objectid
     * @param integer $tourid
     * @param integer $apartmentid
     */
    public function __construct(int $objectid, int $tourid, int $apartmentid)
    {
        $this->objectid = $objectid;

        $this->tourid = $tourid;

        $this->apartmentid = $apartmentid;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        return self::class . "_{$this->objectid}_{$this->tourid}_{$this->apartmentid}";
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

                $result = [];

                $apartmentQuery = ["objectid" => $this->objectid, "apartmentid" => $this->apartmentid];

                $apartment = $frontApi->apartmentList($apartmentQuery)["apartments"][0] ?? [];

                if (!empty($apartment)) {

                    $apartment["age_allows"] = [
                        "main_ages"  => ApartmentHelper::getAllowAges($apartment["main_ages"], $apartment["own_ages"]),
                        "child_ages" => ApartmentHelper::getAllowAges($apartment["child_ages"], $apartment["own_ages"]),
                        "add_ages"   => ApartmentHelper::getAllowAges($apartment["add_ages"], $apartment["own_ages"])
                    ];

                    $pricesQuery = ["apartmentid" => $apartment["id"], "tourid" => $this->tourid];

                    $prices = $frontApi->apartmentPrice($pricesQuery)["apartmentprices"] ?? [];

                    ApartmentHelper::prepareRegularPrices($prices);

                    $apartment["prices"] = $prices[0] ?? [];

                    $offersQuery = ["apartmentid" => $apartment["id"], "tourid" => $this->tourid, "objectid" => $this->objectid];

                    $offer = $frontApi->offers($offersQuery)["offers"][0] ?? [];

                    $apartment["amount_places"] = $offer["amount"] ?? null;

                    $apartment["rooms"] = $offer["rooms"] ?? [];

                    $result[] = $apartment;
            
                }
            
            } catch (\Exception $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
