<?php

namespace Selena\Tasks\Tours;

use DateTime;
use Psr\Http\Client\ClientInterface;
use Selena\Exceptions\ApiException;
use Selena\Resources\Front\FrontApi;
use Selena\Tasks\Subtasks\GetDiscountsForObject;
use Selena\Tasks\Subtasks\GetMinPriceForTour;
use Selena\Tasks\Subtasks\GetOffersForTour;
use Selena\Tasks\TaskContract;

/**
 * Получить список туров для теплохода
 */
class GetTours implements TaskContract
{
    /**
     * ID объекта размещения
     *
     * @var integer
     */
    protected int $objectid;
    /**
     * From
     *
     * @var string|null
     */
    protected ?string $from;
    /**
     * To
     *
     * @var string|null
     */
    protected ?string $to;
    /**
     * Init
     *
     * @param integer $objectid
     * @param DateTime|null $from
     * @param DateTime|null $to
     */
    public function __construct(int $objectid, ?DateTime $from, ?DateTime $to)
    {
        $this->objectid = $objectid;

        $this->from = $from ? $from->format("Y-m-d") : "";

        $this->to = $to ? $to->format("Y-m-d") : "";
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag()
    {
        $class = str_replace('\\', '_', self::class);

        return $class . "_{$this->objectid}_{$this->from}_{$this->to}";
    }
    /**
     * Get callable
     *
     * @return callable
     */
    public function get()
    {
        return function (ClientInterface $client) {

            $frontApi = new FrontApi($client);

            $query = ["objectid" => $this->objectid];

            if (!empty($this->from)) $query["from"] = $this->from;

            if (!empty($this->to)) $query["to"] = $this->to;

            $tours = $frontApi->tourList($query)["tours"] ?? [];

            $offers = $frontApi->offers(["objectid" => $this->objectid, "from" => $this->from, "to" => $this->to])["offers"] ?? [];

            $tourIds = [];

            foreach ($tours as $tour) {

                $tourIds[] = $tour["id"];

                $item = ["tour" => $tour];
                
                $result["tours"][$tour["id"]] = $item;
            }

            foreach($offers as $offer){

                if(in_array($offer["tourid"], $tourIds)){

                    $rooms = $offer["rooms"];

                    $amount = $offer["amount"];

                    $rooms = array_filter($rooms, function($item){ return !empty($item); });

                    $result["tours"][$offer["tourid"]]["amount_places"] = 
                        ($result["tours"][$offer["tourid"]]["amount_places"] ?? 0) + $amount;

                    $result["tours"][$offer["tourid"]]["rooms"] = 
                        ($result["tours"][$offer["tourid"]]["rooms"] ?? 0) + count($rooms);

                }

            }

            return $result ?? null;
        };
    }
}
