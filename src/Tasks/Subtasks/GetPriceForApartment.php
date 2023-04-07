<?php

namespace Selena\Tasks\Subtasks;

use Psr\Http\Client\ClientInterface;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/**
 * Получить цену для апартаментов
 */
class GetPriceForApartment implements TaskContract
{
    /**
     * Apartment id
     *
     * @var integer
     */
    protected int $apartmentid;
    /**
     * Tour id
     *
     * @var integer
     */
    protected int $tourid;
    /**
     * Price prefix
     *
     * @var string
     */
    protected string $pricePrefix;
    /**
     * Init
     *
     * @param integer $objectid
     * @param integer $tourid
     */
    public function __construct(int $apartmentid, int $tourid, string $pricePrefix = "price_m5")
    {
        $this->apartmentid = $apartmentid;

        $this->tourid = $tourid;

        $this->pricePrefix = $pricePrefix;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        $class = str_replace('\\', '_', self::class);

        return $class . "_{$this->apartmentid}_{$this->tourid}_{$this->pricePrefix}"; 
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

                $prices = $frontApi->apartmentPrice(["apartmentid" => $this->apartmentid, "tourid" => $this->tourid]);
                
                $result = $prices["apartmentprices"][0][$this->pricePrefix] ?? null;

                if(!is_null($result)) $result = floatval($result);

            } catch (\Exception $e) {

                $result = null;

            }

            return $result;
        };
    }
}
