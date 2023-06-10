<?php

namespace Selena\Tasks\Units;

use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

/**
 * Получить палубы
 */
class GetUnits implements TaskContract
{
    /**
     * @var integer
     */
    protected int $object_id;

    /**
     * @param integer $object_id
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

        return $cacheFrontApiRepository->unitList($this->object_id);
    }
}
