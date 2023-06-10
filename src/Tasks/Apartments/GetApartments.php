<?php

namespace Selena\Tasks\Apartments;

use Selena\Helpers\ApartmentHelper;
use Selena\Repository\FrontApiCacheRepository;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

class GetApartments implements TaskContract
{

    /**
     * @var int
     */
    protected int $object_id;

    /**
     * @var array
     */
    protected array $unit_ids;

    /**
     * @param int $object_id
     * @param array $unit_ids
     */
    public function __construct(int $object_id, array $unit_ids = [])
    {
        $this->object_id = $object_id;

        $this->unit_ids = $unit_ids;
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

        $apartments = $cacheFrontApiRepository->apartmentList($this->object_id);

        foreach ($this->unit_ids as $unit_id){

            $apartments = array_filter($apartments, fn($item) => $item["unitid"] == $unit_id);

        }

        foreach ($apartments as $item) {
            /**
             * Вычисление разрешенных возрастных категорий
             */
            $item["age_allows"] = [
                "main_ages" => ApartmentHelper::getAllowAges($item["main_ages"], $item["own_ages"]),
                "child_ages" => ApartmentHelper::getAllowAges($item["child_ages"], $item["own_ages"]),
                "add_ages" => ApartmentHelper::getAllowAges($item["add_ages"], $item["own_ages"])
            ];

            $result[] = $item;

        }

        return $result ?? null;
    }
}
