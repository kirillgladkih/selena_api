<?php

namespace Selena\Params\Apartment;

use Selena\Helpers\ApartmentHelper;
use Selena\Params\AbstractParams;

class MainApartmentParams extends AbstractParams
{
    /**
     * Params
     *
     * @var array
     */
    protected $params = [
        /**
         * Param int
         */
        "objectid", 
        /**
         * Param array
         */
        "unitids", 
        /**
         * Param int
         */
        "tourid"
    ];
    /**
     * Filter action
     * 
     * @return mixed
     */
    public function process()
    {
        $childrenCount = count($this->children ?? []);

        foreach ($this->apartments as $apartment) {
            
            $apartment["global_price"] = 0.00;

            if (($this->mainPlaces <= (int) $apartment["places"]) && ($childrenCount <= (int) $apartment["childplaces"])) {

                $category_id = ApartmentHelper::getPriceCategory($apartment["own_ages"], 17) ?? "";

                $priceName = "price_m{$category_id}";

                $price = (float) ($apartment["prices"][$priceName] ?? 0.00);

                $apartment["global_price"] =  $apartment["global_price"] + ($price * $this->mainPlaces);

                $childAgesCount = count($apartment["age_allows"]["child_ages"]);

                if ($childrenCount >= 1) {

                    if ($childAgesCount >= 1) {

                        $from = (int) ($apartment["age_allows"]["child_ages"][0]["from"] ?? 0);

                        $to = (int) ($apartment["age_allows"]["child_ages"][$childAgesCount - 1]["to"] ?? 16);

                        $childrenAgeCondition = true;

                        foreach ($this->children as $age) {

                            $childrenAgeCondition = $age <= $to && $age >= $from;

                            if ($childrenAgeCondition) {

                                $category_id = ApartmentHelper::getPriceCategory($apartment["own_ages"], $age) ?? "";

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
}
