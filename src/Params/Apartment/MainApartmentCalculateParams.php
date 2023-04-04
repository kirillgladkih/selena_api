<?php

namespace Selena\Params\Apartment;

use Selena\Params\AbstractParams;

class MainApartmentCalculateParams extends AbstractParams
{
    /**
     * Params
     *
     * @var array
     */
    protected $params = [
        /**
         * Array apartments from GetApartments task
         */
        "apartments",
        /**
         * Array prices from GetApartmentsPrices task
         */
        "prices",
        /**
         * Array children age, ex: [5, 4]
         */
        "children",
        /**
         * Count main places
         */
        "mainPlaces"
    ];
}
