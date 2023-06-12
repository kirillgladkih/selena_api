<?php

namespace Selena\Tasks\Apartments;

use Selena\Helpers\ApartmentHelper;
use Selena\Repository\FrontApiCacheRepository;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;
use Selena\Tasks\TaskHandler;

/**
 * Апартаметы детально
 */
class GetApartmentDetail implements TaskContract
{

    /**
     * @var int
     */
    protected int $object_id;

    /**
     * @var int
     */
    protected int $tour_id;

    /**
     * @var int
     */
    protected int $apartment_id;

    /**
     * @param int $object_id
     * @param int $tour_id
     * @param int $apartment_id
     */
    public function __construct(int $object_id, int $tour_id, int $apartment_id)
    {
        $this->apartment_id = $apartment_id;

        $this->tour_id = $tour_id;

        $this->object_id = $object_id;
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

        /**
         * @var TaskHandler $handler
         */
        $handler = SelenaService::instance()->get(TaskHandler::class);

        $apartment = $cacheFrontApiRepository->apartmentList($this->object_id, $this->apartment_id)[0] ?? [];

        if (!empty($apartment)) {

            $apartment["age_allows"] = [
                "main_ages" => ApartmentHelper::getAllowAges($apartment["main_ages"], $apartment["own_ages"]),
                "child_ages" => ApartmentHelper::getAllowAges($apartment["child_ages"], $apartment["own_ages"]),
                "add_ages" => ApartmentHelper::getAllowAges($apartment["add_ages"], $apartment["own_ages"])
            ];

            $apartment["prices"] = $handler->handle(\Selena\Tasks\Subtasks\GetPriceForApartment::class, ["apartment_id" => $this->apartment_id, "tour_id" => $this->tour_id]);

            $offers = $handler->handle(\Selena\Tasks\Subtasks\GetApartmentOffers::class, [
                "object_id" => $this->object_id,
                "tour_id" => $this->tour_id,
                "apartment_id" => $this->apartment_id
            ]);

            $apartment["amount_places"] = $offers["amount"] ?? null;

            $apartment["rooms"] = $offers["offers"][0]["rooms"] ?? [];

            $result = $apartment;
        }

        return $result ?? null;

    }
}
