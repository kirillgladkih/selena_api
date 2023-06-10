<?php

namespace Selena\Tasks\Services;

use Psr\Http\Client\ClientInterface;
use Selena\Repository\FrontApiCacheRepository;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

class GetServices implements TaskContract
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

        return $cacheFrontApiRepository->serviceList($this->object_id);
    }
}
