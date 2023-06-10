<?php

namespace Selena\Tasks\Subtasks;

use Selena\Repository\FrontApiCacheRepository;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

/**
 * Получить свободные места
 */
class GetApartmentOffers implements TaskContract
{

    /**
     * @var int|null
     */
    protected ?int $apartment_id = null;

    /**
     * @var int
     */
    protected int $object_id;

    /**
     * @var int
     */
    protected int $tour_id;

    /**
     * @param int $object_id
     * @param int $tour_id
     * @param int|null $apartment_id
     */
    public function __construct(int $object_id, int $tour_id, ?int $apartment_id = null)
    {
        $this->apartment_id = $apartment_id;

        $this->object_id = $object_id;

        $this->tour_id = $tour_id;
    }

    /**
     * @return array|null
     */
    public function get(): ?array
    {
        /**
         * @var FrontApiCacheRepository $cacheFrontApiRepository
         */
        $cacheFrontApiRepository = SelenaService::instance()->get(FrontApiCacheRepository::class);

        $offers = $cacheFrontApiRepository->offers($this->object_id, $this->tour_id);

        foreach ($offers as $offer){

            if(isset($this->apartment_id) && $offer["apartmentid"] != $this->apartment_id){

                continue;

            }

            $offer["rooms"] = array_filter($offer["rooms"], fn($item) => !empty($item));

            $result["offers"][] = $offer;

            $result["amount"] = ($result["amount"] ?? 0) + ($offer["amount"] ?? 0);

        }

        return $result ?? null;
    }
}
