<?php

namespace Selena\Helpers;

class ApartmentHelper
{
    /**
     * Получить разрешенные категории для мест из битовой карты
     *
     * @param string $map
     * @param array $ageCategories
     * @return array
     */
    public static function getAllowAges(string $map, array $ageCategories)
    {
        $result = [];

        $allows = array_map(fn ($value) => (int) $value, str_split($map, 1));

        foreach ($allows as $key => $allow) if ($allow) $result[] = $ageCategories[$key];

        return $result;
    }
    /**
     * Подготовить цены с флагом regular=true
     *
     * @param array $prices
     * @return void
     */
    public static function prepareRegularPrices(array &$prices)
    {
        foreach ($prices ?? [] as $itemPrice) {

            $priceKeys = preg_grep("/price_/", array_keys($itemPrice));

            foreach ($priceKeys ?? [] as $priceKey) {
                /**
                 * Если установлен флаг "regular" то необходимо домножить ценну на длительность тура
                 */
                $coefficient = ($itemPrice["regular"] ?? false) ? $itemPrice["duration_to"] : 1;

                if (isset($itemPrice[$priceKey]) && $itemPrice[$priceKey] != 0){

                    $prices[$priceKey] = (float) $itemPrice[$priceKey] * (float) $coefficient;

                }
            }
        }
    }
    /**
     * Get price category
     *
     * @param array $own_ages
     * @param integer $age
     * @return integer|null
     */
    public static function getPriceCategory(array $own_ages, int $age)
    {
        $category_id = null;

        foreach ($own_ages as $category) {

            $from = (int) ($category["from"] ?? 0);

            $to = (int) ($category["to"] ?? 999);

            if ($age >= $from && $age < $to) {

                $category_id = $category["category"];

                break;
            }
        }

        return $category_id;
    }
    /**
     * Search price by apartment id
     *
     * @param array $prices
     * @param string $priceName
     * @param integer $apartmentId
     * @return float|null
     */
    public static function searchPriceByApartmentId(array $prices, string $priceName, int $apartmentId): ?float
    {
        foreach ($prices as $items)
            if ($items["apartmentid"] == $apartmentId)
                foreach ($items["prices"] as $key => $value)
                    if ($key == $priceName)
                        return (float) $value;

        return null;
    }
}
