<?php

namespace Selena\Tasks\Services;

use Psr\Http\Client\ClientInterface;
use Selena\Helpers\CacheHelper;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\TaskContract;

/*
 * Получить списока экскурсий
 */

class GetExcursions implements TaskContract
{
    /**
     * Tour id
     *
     * @var integer
     */
    protected int $tourId;
    /**
     * Object id
     *
     * @var integer
     */
    protected int $objectid;
    /**
     * Cache helper
     *
     * @var CacheHelper
     */
    protected CacheHelper $cacheHelper;
    /*
     * Init
     *
     * @param integer $objectid
     */
    public function __construct(int $tourId, int $objectid)
    {
        $this->tourId = $tourId;

        $this->objectid = $objectid;

        $this->cacheHelper = new CacheHelper();
    }
    /*
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        // $ids = implode("_", $this->tourIds);

        $class = str_replace('\\', '_', self::class);

        return $class . "_{$this->tourId}";
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

                $data = $this->getDataWithCache($frontApi);

                $groupIds = [];

                foreach($data["groups"] ?? [] as $group)                    
                    if(in_array($this->tourId, $group["tour_ids" ?? []]))
                        $groupIds[] = $group["groupid"];

                foreach($data["excursions"] ?? [] as $excursion){
                    
                    if(in_array($excursion["groupid"] ?? "", $groupIds)){

                        if(!isset($result["group"])) $result["group"] = $data["tour"][0] ?? [];

                        $result["group"]["excursions"][] = $excursion; 

                    }
                }

                sort($result["group"]["excursions"]);


            } catch (\Exception $exception) {

                $result = null;
            }

            return $result;
        };
    }
    /**
     * Get data with cahce
     *
     * @return array
     */
    private function getDataWithCache($frontApi)
    {
        $tourParams = ["objectid" => $this->objectid, "tourid" => $this->tourId];
               
        $groupsParams = ["objectid" => $this->objectid, "tourid" => $this->tourId];

        $excursionsParams = ["objectid" => $this->objectid];

        $tourClosure = fn() => $frontApi->tourList($tourParams);

        $groupsClosure = fn() => $frontApi->serviceGroupList($groupsParams);

        $excursionsClosure = fn() => $frontApi->serviceList($excursionsParams);

        return [
            "tour" => $this->cacheHelper->withCache( "tours_" . http_build_query([$tourParams]), $tourClosure)["tours"] ?? [],
            "groups" => $this->cacheHelper->withCache( "group_" . http_build_query([$groupsParams]), $groupsClosure)["servicegroups"] ?? [],
            "excursions" => $this->cacheHelper->withCache( "excursion_" . http_build_query([$excursionsParams]), $excursionsClosure)["services"] ?? []
        ];
    }
}
