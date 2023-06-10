<?php

namespace Selena\Tasks\Tours;

use Selena\Repository\FrontApiCacheRepository;
use Selena\SelenaService;
use Selena\Tasks\Subtasks\GetDiscountsForObject;
use Selena\Tasks\TaskContract;
use Selena\Tasks\TaskHandler;

class GetTourDetail implements TaskContract
{
    /**
     * @var integer
     */
    protected int $object_id;

    /**
     * @var integer
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

        $tour = $cacheFrontApiRepository->tourList($this->object_id, $this->tour_id)[0] ?? [];

        if(empty($tour)) {

            $result = [
                "tour" => $tour,
                "discounts" => $handler->handle(GetDiscountsForObject::class, $this->object_id)
            ];

        }
        return $result ?? null;
    }
}
