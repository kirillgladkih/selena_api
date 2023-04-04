<?php

namespace Selena\Tasks\Subtasks;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/**
 * Получить минимальную ценну для тура
 */
class GetMinPriceForTour implements TaskContract
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

                $tour = $frontApi->tourList(["objectid" => $this->objectid, "tourid" => $this->tourid]);

                $offers = $frontApi->offers(["objectid" => $this->objectid, "tourid" => $this->tourid])["offers"] ?? [];

                $allowApartments = [];

                $prices = [];

                foreach ($offers as $offer) $allowApartments[] = $offer["apartmentid"];
                /**
                 * Получение апартаментов
                 */
                if (!$apartmentIds = $tour["tours"][0]["apartment_ids"] ?? null) {

                    $apartments = $frontApi->apartmentList(["objectid" => $this->objectid]);

                    foreach ($apartments["apartments"] ?? [] as $apartment)
                        if (in_array($apartment["id"], $allowApartments))
                            $apartmentIds[] = $apartment["id"];
                }
                /**
                 * Формирование масива цен для сравнения
                 */
                foreach ($apartmentIds as $apartmentId) {

                    try {

                        $price = $frontApi->apartmentPrice(["apartmentid" => $apartmentId, "tourid" => $this->tourid])["apartmentprices"][0] ?? [];

                        $prices[$apartmentId] = $price;
                        /**
                         * Если цена не была найдена идем дальше
                         */
                    } catch (ApiException $exception) {

                        continue;
                    }
                }
                /**
                 * Поиск минимальной цены (учитывается только минимальная цена основного места)
                 */
                foreach ($prices as $itemPrice) {

                    $priceKeys = preg_grep("/price_m/", array_keys($itemPrice));

                    foreach ($priceKeys ?? [] as $priceKey) {
                        /**
                         * Если установлен флаг "regular" то необходимо домножить ценну на длительность тура
                         */
                        $coefficient = ($itemPrice["regular"] ?? false) ? $tour["duration"] : 1;

                        $comparablePrice = (float) $itemPrice[$priceKey] * (float) $coefficient;

                        if (!isset($minimumPrice)) $minimumPrice = $comparablePrice;

                        $minimumPrice = ($minimumPrice > $comparablePrice) ? $comparablePrice : $minimumPrice;
                    }
                }

                $result = $minimumPrice;
            } catch (\Exception $e) {

                $result = null;
            }

            return $result;
        };
    }
}
