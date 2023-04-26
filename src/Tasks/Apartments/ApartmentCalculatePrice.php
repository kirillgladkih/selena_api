<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Helpers\ApartmentHelper;
use Selena\Params\Apartment\MainApartmentCalculateParams;
use Selena\Tasks\TaskContract;

/*
 * Высчитать цену
 */

class ApartmentCalculatePrice implements TaskContract
{
    /**
     * Params
     *
     * @var MainApartmentCalculateParams
     */
    protected MainApartmentCalculateParams $params;
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
    public function __construct(MainApartmentCalculateParams $params)
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

                $childrenCount = count($this->params->children ?? []);

                foreach ($this->params->apartments as $apartment) {

                    $places = [];

                    for ($i = 0; $i < $this->params->mainPlaces; $i++) $places[] = 18;

                    $places = array_merge($places, $this->params->children ?? []);

                    $item = [
                        "apartmentid" => $apartment["id"],
                        "children" => $childrenCount,
                        "mainPlaces" => $this->params->mainPlaces,
                        "price" => 0.00,
                    ];

                    foreach ($places as $age) {

                        $category_id = ApartmentHelper::getPriceCategory($apartment["own_ages"], $age) ?? "";

                        $priceName = "price_m{$category_id}";

                        $price = ApartmentHelper::searchPriceByApartmentId($this->params->prices, $priceName, $apartment["id"]) ?? 0.00;

                        $item["price"] = $item["price"] + $price;
                        
                        $item["prices"] = $this->params->prices;

                    }
                    
                    $result[] = $item;
                }

            return $result ?? null;
        };
    }
}
