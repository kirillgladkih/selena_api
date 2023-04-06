<?php

namespace Selena\Tasks\Services;

use Psr\Http\Client\ClientInterface;
use Selena\Helpers\ApartmentHelper;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 * Получить список апартаментов
 */

class GetServices implements TaskContract
{
    /*
     * ID объекта размещения
     *
     * @var integer
     */
    protected int $objectid;
    /*
     * Init
     *
     * @param integer $objectid
     */
    public function __construct(int $objectid)
    {
        $this->objectid = $objectid;
    }
    /*
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {

        $class = str_replace('\\', '_', self::class);

        return $class . "_{$this->objectid}";
    }
    /*
     * Get callable
     *
     * @return callable
     */
    public function get()
    {
        return function (ClientInterface $client) {

            try {

                $frontApi = new FrontApi($client);

                $services = $frontApi->serviceList(["objectid" => $this->objectid])["services"] ?? null;
                
                return $services;

            } catch (\Exception $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
