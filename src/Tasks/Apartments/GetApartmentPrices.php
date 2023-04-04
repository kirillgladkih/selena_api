<?php

namespace Selena\Tasks\Apartments;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Helpers\ApartmentHelper;
use Selena\Params\Apartment\MainApartmentParams;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 *  Получить цены для апартаментов
 */

class GetApartmentPrices implements TaskContract
{
    /**
     * Params
     *
     * @var MainApartmentParams
     */
    protected MainApartmentParams $params;
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
    public function __construct(MainApartmentParams $params, $apartments)
    {
        $this->params = $params;

        $this->apartments = $apartments;
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

                $frontApi = new FrontApi($client);

                foreach ($this->apartments ?? [] as $key => $item) {

                    $pricesQuery = ["apartmentid" => $item["id"], "tourid" => $this->params->tourid];

                    try {
                        /**
                         * Получение цен
                         */
                        $prices = $frontApi->apartmentPrice($pricesQuery)["apartmentprices"] ?? [];

                        ApartmentHelper::prepareRegularPrices($prices);

                        $prices = $prices[0] ?? [];

                        if (!empty($prices)) $result[] = ["apartmentid" => $item["id"], "prices" => $prices];
                        /**
                         * Exception
                         */
                    } catch (ApiException $exception) {

                        continue;
                    }
                }
            } catch (\Exception $exception) {

                $result = null;
            }

            return $result ?? null;
        };
    }
}
