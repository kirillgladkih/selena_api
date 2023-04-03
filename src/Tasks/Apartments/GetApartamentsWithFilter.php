<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Filter\Apartment\MainApartmentFilter;
use Selena\Helpers\ApartmentHelper;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 * Получить список апартаментов и отфильтровать их
 */

class GetApartamentsWithFilter implements TaskContract
{
    /**
     * Filter
     *
     * @var MainApartmentFilter
     */
    protected MainApartmentFilter $filter;
    /*
     * Init
     *
     */
    public function __construct(MainApartmentFilter $filter)
    {
        $this->filter = $filter;
    }
    /*
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
    
        $class = preg_replace("/\//", "_", self::class);

        return $class . "_" . http_build_query($this->filter->toArray());
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

                $result = [];

                $apartments = [];

                $apartmentQuery = ["objectid" => $this->filter->objectid];
                /**
                 * Получение апартаметов по палубам
                 */
                if (!empty($this->filter->unitids)) {

                    foreach ($this->filter->unitids as $id) {

                        $apartmentQuery["unitid"] = $id;

                        $aps = $frontApi->apartmentList($apartmentQuery)["apartments"] ?? [];

                        $apartmentsRaw = array_merge($apartments, $aps);
                    }
                    
                } else {

                    $apartmentsRaw = $frontApi->apartmentList($apartmentQuery)["apartments"] ?? [];
                
                }

                foreach ($apartmentsRaw as $key => $apartment) {
                    /**
                     * Вычисление разрешенных возрастных категорий
                     */
                    $apartmentItem["age_allows"] = [
                        "main_ages"  => ApartmentHelper::getAllowAges($apartment["main_ages"], $apartment["own_ages"]),
                        "child_ages" => ApartmentHelper::getAllowAges($apartment["child_ages"], $apartment["own_ages"]),
                        "add_ages"   => ApartmentHelper::getAllowAges($apartment["add_ages"], $apartment["own_ages"])
                    ];

                    $pricesQuery = ["apartmentid" => $apartment["id"], "tourid" => $this->filter->tourid];

                    try {
                        /**
                         * Получение цен
                         */
                        $prices = $frontApi->apartmentPrice($pricesQuery)["apartmentprices"] ?? [];

                        ApartmentHelper::prepareRegularPrices($prices);

                        $apartmentItem["prices"] = $prices[0] ?? [];

                        $offersQuery = ["apartmentid" => $apartment["id"], "tourid" => $this->filter->tourid, "objectid" => $this->filter->objectid];

                        $offer = $frontApi->offers($offersQuery)["offers"][0] ?? [];

                        $apartmentItem[$key]["amount_places"] = $offer["amount"] ?? null;

                        $apartmentItem["rooms"] = $offer["rooms"] ?? [];

                        $apartments[] = $apartmentItem;

                    } catch (ApiException $exception) {

                        continue;
                    }
                }

                $this->filter->extend("apartments", $apartments);

                $result = $this->filter->process();

            } catch (\Exception $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
