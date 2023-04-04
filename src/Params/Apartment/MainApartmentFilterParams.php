<?php

namespace Selena\Params\Apartment;

use Selena\Helpers\ApartmentHelper;
use Selena\Params\AbstractParams;

class MainApartmentFilterParams extends AbstractParams
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
         * Array offers from GetApartmentsOffers task
         */
        "offers",
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
