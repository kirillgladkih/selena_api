<?php

namespace Selena\Tasks\Services;

use Psr\Http\Client\ClientInterface;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

/*
 * Получить списока экскурсий
 */

class GetExcursions implements TaskContract
{
    /**
     * @var integer
     */
    protected int $object_id;

    /**
     * @var int
     */
    protected int $tour_id;

    /**
     * @param $object_id
     * @param $tour_id
     */
    public function __construct($object_id, $tour_id)
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

        $tour = $cacheFrontApiRepository->tourList($this->object_id, $this->tour_id);

        $excursions = $cacheFrontApiRepository->tourStandList($this->tour_id);

        $result["tour"] = $tour;

        foreach ($excursions as $key => $excursion) {

            if (!isset($excursion["begindate"])) $excursions[$key]["begindate"] = $excursion["enddate"];

            if (!isset($excursion["enddate"])) $excursions[$key]["enddate"] = $excursion["begindate"];
        }

        usort($excursions, fn($a, $b) => $a["begindate"] <=> $b["begindate"]);

        $start = $excursions[0]["begindate"] ?? $excursions[0]["enddate"];

        $day = 1;

        if ($start) {

            foreach ($excursions as $key => $excursion) {


                if (date("Y-m-d", strtotime($excursion["begindate"])) > date("Y-m-d", strtotime($start))) {

                    $start = $excursion["begindate"] ?? $excursion["enddate"];

                    $day += 1;
                }


                $time_duration = strtotime($excursion["enddate"]) - strtotime($excursion["begindate"]);

                $excursion["time_duration"] = $time_duration / 3600;

                $excursion["day"] = $day;

                $result["excursions"][$day] = $excursion;
            }
        }

        return $result ?? null;
    }
}
