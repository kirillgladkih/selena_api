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
        $class = str_replace('\\', '_', self::class);

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
                
                $items = $this->filter->apartments ?? [];

                foreach ($items as $key => $item) {
                    /**
                     * Вычисление разрешенных возрастных категорий
                     */
                    $item["age_allows"] = [
                        "main_ages"  => ApartmentHelper::getAllowAges($item["main_ages"], $item["own_ages"]),
                        "child_ages" => ApartmentHelper::getAllowAges($item["child_ages"], $item["own_ages"]),
                        "add_ages"   => ApartmentHelper::getAllowAges($item["add_ages"], $item["own_ages"])
                    ];

                    $pricesQuery = ["apartmentid" => $item["id"], "tourid" => $this->filter->tourid];

                    try {
                        /**
                         * Получение цен
                         */
                        $prices = $frontApi->apartmentPrice($pricesQuery)["apartmentprices"] ?? [];

                        ApartmentHelper::prepareRegularPrices($prices);

                        $item["prices"] = $prices[0] ?? [];

                        $offersQuery = ["apartmentid" => $item["id"], "tourid" => $this->filter->tourid, "objectid" => $this->filter->objectid];

                        $offer = $frontApi->offers($offersQuery)["offers"][0] ?? [];

                        $item[$key]["amount_places"] = $offer["amount"] ?? null;

                        $item["rooms"] = $offer["rooms"] ?? [];

                        $apartments[] = $item;

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
