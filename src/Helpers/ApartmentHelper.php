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

                if (isset($itemPrice[$priceKey]) && $itemPrice[$priceKey] != 0)
                    $prices[$priceKey] = (float) $itemPrice[$priceKey] * (float) $coefficient;
            }
        }
    }
    /**
     * Фильтровать апартаменты и высчитать цены
     *
     * @param array $apartments
     * @param integer $mainPlaces
     * @param array $children
     * @return array
     */
    public static function filterApartmentAndCalculatePrice(array $apartments, int $mainPlaces, array $children)
    {
        $childrenCount = count($children);

        foreach ($apartments as $apartment) {

            $apartment["global_price"] = 0.00;

            if (($mainPlaces <= (int) $apartment["places"]) && ($childrenCount <= (int) $apartment["childplaces"])) {

                $category_id = self::getPriceCategory($apartment["own_ages"], 17) ?? "";

                $priceName = "price_m{$category_id}";

                $price = (float) ($apartment["prices"][$priceName] ?? 0.00);

                $apartment["global_price"] =  $apartment["global_price"] + ($price * $mainPlaces);

                $childAgesCount = count($apartment["age_allows"]["child_ages"]);

                if ($childrenCount >= 1) {

                    if ($childAgesCount >= 1) {

                        $from = (int) ($apartment["age_allows"]["child_ages"][0]["from"] ?? 0);

                        $to = (int) ($apartment["age_allows"]["child_ages"][$childAgesCount - 1]["to"] ?? 16);

                        $childrenAgeCondition = true;

                        foreach ($children as $age) {

                            $childrenAgeCondition = $age <= $to && $age >= $from;

                            if ($childrenAgeCondition) {

                                $category_id = self::getPriceCategory($apartment["own_ages"], $age) ?? "";

                                $priceName = "price_c{$category_id}";

                                $price = (float) ($apartment["prices"][$priceName] ?? 0.00);

                                $apartment["global_price"] = $apartment["global_price"] +  $price;

                                $result[] = $apartment;

                                break;
                            }
                        }
                    }

                } else {

                    $result[] = $apartment;
                
                }
            }
        }

        return $result ?? [];
    }

    /**
     * Get price category
     *
     * @param array $own_ages
     * @param integer $age
     * @return integer|null
     */
    public static function getPriceCategory(array $own_ages, int $age): ?int
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
}
