<?php

namespace Selena\Tasks\Tours;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Selena\Tasks\Subtasks\GetDiscountsForObject;
use Selena\Tasks\Subtasks\GetMinPriceForTour;
use Selena\Tasks\Subtasks\GetOffersForTour;
use Selena\Tasks\TaskContract;

/**
 * Получить cписок видов путёвок
 */
class GetTourPack implements TaskContract
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

        return $cacheFrontApiRepository->tourPackList($this->object_id);
    }
}
