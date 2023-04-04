<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Filter\Apartment\MainApartmentFilter;
use Selena\Helpers\ApartmentHelper;
use Selena\Params\Apartment\MainApartmentFilterParams;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 * Отфильтровать апартаменты
 */

class ApartmentsFilter implements TaskContract
{
    /**
     * Filter params
     *
     * @var MainApartmentFilterParams
     */
    protected MainApartmentFilterParams $params;
    /*
     * Init
     *
     */
    public function __construct(MainApartmentFilterParams $params)
    {
        $this->params = $params;
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

            try {

                $childrenCount = count(($this->params->children ?? []));

                foreach ($this->params->apartments as $apartment) {

                    // $apartment["global_price"] = 0.00;

                    if (($this->params->mainPlaces <= (int) $apartment["places"]) && ($childrenCount <= (int) $apartment["childplaces"])) {

                        // $category_id = ApartmentHelper::getPriceCategory($apartment["own_ages"], 17) ?? "";

                        // $priceName = "price_m{$category_id}";

                        // $price = (float) ($apartment["prices"][$priceName] ?? 0.00);

                        // $apartment["global_price"] =  $apartment["global_price"] + ($price * $this->mainPlaces);

                        $childAgesCount = count($apartment["age_allows"]["child_ages"]);

                        if ($childrenCount >= 1) {

                            if ($childAgesCount >= 1) {

                                $from = (int) ($apartment["age_allows"]["child_ages"][0]["from"] ?? 0);

                                $to = (int) ($apartment["age_allows"]["child_ages"][$childAgesCount - 1]["to"] ?? 16);

                                $childrenAgeCondition = true;

                                foreach ($this->params->children as $age) {

                                    $childrenAgeCondition = $age <= $to && $age >= $from;

                                    if ($childrenAgeCondition) {

                                        // $category_id = ApartmentHelper::getPriceCategory($apartment["own_ages"], $age) ?? "";

                                        // $priceName = "price_c{$category_id}";

                                        // $price = (float) ($apartment["prices"][$priceName] ?? 0.00);

                                        // $apartment["global_price"] = $apartment["global_price"] +  $price;

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
            } catch (\Exception $exception) {
                dd($exception->getMessage());
                $result = null;
            }

            return $result;
        };
    }
}
