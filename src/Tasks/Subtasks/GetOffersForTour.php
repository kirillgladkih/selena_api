<?php

namespace Selena\Tasks\Subtasks;

use Selena\Repository\FrontApiCacheRepository;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

class GetOffersForTour implements TaskContract
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
     * @param int $object_id
     * @param int $tour_id
     */
    public function __construct(int $object_id, int $tour_id)
    {
        $this->object_id = $object_id;

        $this->tour_id = $tour_id;
    }

    /**
     * @return int|null
     */
    public function get(): ?int
    {
        /**
         * @var FrontApiCacheRepository $cacheFrontApiRepository
         */
        $cacheFrontApiRepository = SelenaService::instance()->get(FrontApiCacheRepository::class);

        $offers = $cacheFrontApiRepository->offers($this->object_id, $this->tour_id);

        foreach ($offers ?? [] as $offer) {

            $result = intval($result ?? 0) + intval($offer["amount"] ?? 0);

        }

        return $result ?? null;
    }
}
