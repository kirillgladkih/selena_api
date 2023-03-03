<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Helpers\ApartmentHelper;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/**
 * Получить список апартаментов по палубам вместе с ценами и свободными местами
 */
class GetApartmentsWithPrices implements TaskContract
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
     * ID палубы
     *
     * @var integer[]
     */
    protected array $unitids;
    /**
     * Init
     *
     * @param integer $objectid
     * @param integer $tourid
     * @param integer[] $unitids
     */
    public function __construct(int $objectid, int $tourid, array $unitids = [])
    {
        $this->objectid = $objectid;

        $this->tourid = $tourid;

        $this->unitids = $unitids;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag(): string
    {
        $unitids = implode("_", $this->unitids);

        return self::class . "_{$this->objectid}_{$this->tourid}_{$unitids}";
    }
    /**
     * Get callable
     *
     * @return callable
     */
    public function get(): callable
    {
        return function (ClientInterface $client) {

            try {

                $frontApi = new FrontApi($client);

                $result = [];

                $apartments = [];

                $apartmentQuery = ["objectid" => $this->objectid];
                /**
                 * Получение апартаметов по палубам
                 */
                if (!empty($this->unitids)) {

                    foreach ($this->unitids as $id) {

                        $apartmentQuery["unitid"] = $id;

                        $aps = $frontApi->apartmentList($apartmentQuery)["apartments"] ?? [];

                        $apartments = array_merge($apartments, $aps);
                    }
                    
                } else {

                    $apartments = $frontApi->apartmentList($apartmentQuery)["apartments"] ?? [];
                }

                foreach ($apartments as $apartment) {
                    /**
                     * Вычисление разрешенных возрастных категорий
                     */
                    $apartment["age_allows"] = [
                        "main_ages"  => ApartmentHelper::getAllowAges($apartment["main_ages"], $apartment["own_ages"]),
                        "child_ages" => ApartmentHelper::getAllowAges($apartment["child_ages"], $apartment["own_ages"]),
                        "add_ages"   => ApartmentHelper::getAllowAges($apartment["add_ages"], $apartment["own_ages"])
                    ];

                    $pricesQuery = ["apartmentid" => $apartment["id"], "tourid" => $this->tourid];

                    try {
                        /**
                         * Получение цен
                         */
                        $prices = $frontApi->apartmentPrice($pricesQuery)["apartmentprices"] ?? [];

                        ApartmentHelper::prepareRegularPrices($prices);

                        $apartment["prices"] = $prices[0] ?? [];

                        $offersQuery = ["apartmentid" => $apartment["id"], "tourid" => $this->tourid, "objectid" => $this->objectid];

                        $offer = $frontApi->offers($offersQuery)["offers"][0] ?? [];

                        $apartment["amount_places"] = $offer["amount"] ?? null;

                        $apartment["rooms"] = $offer["rooms"] ?? [];

                        $result[] = $apartment;
                    
                    } catch (ApiException $exception) {

                        continue;
                    }
                }

            } catch (\Exception $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
