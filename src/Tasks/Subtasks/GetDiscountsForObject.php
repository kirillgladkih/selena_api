<?php

namespace Selena\Tasks\Subtasks;

use Psr\Http\Client\ClientInterface;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/**
 * Получить скидки
 */
class GetDiscountsForObject implements TaskContract
{
    /**
     * ID объекта размещения
     *
     * @var integer
     */
    protected int $objectid;
    /**
     * Init
     *
     * @param integer $objectid
     * @param integer $tourid
     */
    public function __construct(int $objectid)
    {
        $this->objectid = $objectid;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag(): string 
    {
        return self::class . "_{$this->objectid}"; 
    }
    /**
     * Get callable
     *
     * @return callable
     */
    public function get(): callable
    {
        return function (ClientInterface $client) {
            
            try {
             
                $frontApi = new FrontApi($client);

                $result = $frontApi->discountList(["objectid" => $this->objectid])["discounts"] ?? [];

            } catch (\Exception $e) {

                $result = null;

            }

            return $result;
        };
    }
}
