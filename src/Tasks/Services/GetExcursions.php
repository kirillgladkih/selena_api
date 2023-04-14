<?php

namespace Selena\Tasks\Services;

use Psr\Http\Client\ClientInterface;
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
     * @var int
     */
    protected int $tourid;
    /**
     * Object id
     *
     * @var integer
     */
    protected int $objectid;
    /*
     * Init
     *
     * @param integer $objectid
     */
    public function __construct(int $tourid, int $objectid)
    {
        $this->tourid = $tourid;

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

        return $class . "_{$this->tourid}_{$this->objectid}";
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

                $result = [];

                $tour = $frontApi->tourList(["objectid" => $this->objectid, "tourid" => $this->tourid])["tours"][0] ?? [];

                $excursions = $frontApi->tourStandList(["tourid" => $this->tourid])["tourstands"] ?? [];

                $result["tour"] = $tour;

                foreach($excursions as $key => $excursion){

                    if(!isset($excursion["begindate"])) $excursions[$key]["begindate"] = $excursion["enddate"];

                    if(!isset($excursion["enddate"])) $excursions[$key]["enddate"] = $excursion["begindate"];

                }

                usort($excursions, fn ($a, $b) => $a["begindate"] <=> $b["begindate"]);

                $start = $excursions[0]["begindate"] ?? $excursions[0]["enddate"];

                $day = 1;

                if ($start) {

                    foreach ($excursions as $key => $excursion) {
 

                        if(date("Y-m-d", strtotime($excursion["begindate"])) > date("Y-m-d", strtotime($start))) {
                            
                            $start = $excursion["begindate"] ?? $excursion["enddate"];

                            $day += 1;
                        
                        }


                        $time_duration = strtotime($excursion["enddate"]) - strtotime($excursion["begindate"]);

                        $excursion["time_duration"] = $time_duration / 3600;

                        $excursion["day"] = $day;                        

                        $result["excursions"][$day] = $excursion;

                    }
                }
            } catch (\Exception $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
