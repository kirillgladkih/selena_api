<?php

namespace Selena\Tasks\Subtasks;

use Selena\Repository\FrontApiCacheRepository;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

/**
 * Получить скидки
 */
class GetDiscountsForObject implements TaskContract
{

    /**
     * @var int
     */
    protected int $object_id;

    /**
     * @param int $object_id
     */
    public function __construct(int $object_id)
    {
        $this->object_id = $object_id;
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

        return $cacheFrontApiRepository->discountList($this->object_id);
    }
}
