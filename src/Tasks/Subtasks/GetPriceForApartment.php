<?php

namespace Selena\Tasks\Subtasks;

use Psr\Http\Client\ClientInterface;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

/**
 * Получить цену для апартаментов
 */
class GetPriceForApartment implements TaskContract
{

    /**
     * @var int
     */
    protected int $apartment_id;

    /**
     * @var int
     */
    protected int $tour_id;

    /**
     * @var string|null
     */
    protected ?string $price_prefix;

    /**
     * @param int $apartment_id
     * @param int $tour_id
     * @param string|null $price_prefix
     */
    public function __construct(int $apartment_id, int $tour_id, ?string $price_prefix = null)
    {
        $this->apartment_id = $apartment_id;

        $this->tour_id = $tour_id;

        $this->price_prefix = $price_prefix;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        /**
         * @var FrontApiCacheRepository $cacheFrontApiRepository
         */
        $cacheFrontApiRepository = SelenaService::instance()->get(FrontApiCacheRepository::class);

        $prices = $cacheFrontApiRepository->apartmentPrices($this->apartment_id, $this->tour_id);

        $prices = reset($prices);

        if(isset($this->price_prefix)){

            $prices = $prices[$this->price_prefix] ?? false;

        }

        return $prices;
    }
}
