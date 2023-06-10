<?php

namespace Selena\Tasks\Tours;

use DateTime;
use Psr\Http\Client\ClientInterface;
use Selena\Repository\FrontApiCacheRepository;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Selena\Tasks\TaskContract;

/**
 * Получить список туров для теплохода
 */
class GetTours implements TaskContract
{

    /**
     * @var int
     */
    protected int $object_id;

    /**
     * @var string|null
     */
    protected ?string $from;

    /**
     * @var string|null
     */
    protected ?string $to;

    /**
     * Init
     *
     * @param integer $object_id
     * @param DateTime|null $from
     * @param DateTime|null $to
     */
    public function __construct(int $object_id, ?DateTime $from = null, ?DateTime $to = null)
    {
        $this->object_id = $object_id;

        $this->from = $from ? $from->format("Y-m-d") : null;

        $this->to = $to ? $to->format("Y-m-d") : null;
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

        $tours = $cacheFrontApiRepository->tourList($this->object_id);

        $offers = $cacheFrontApiRepository->offers($this->object_id);

        $tourIds = [];

        foreach ($tours as $tour) {

            if(isset($this->from) && strtotime($this->from) >= strtotime($tour["begindate"])){

                continue;

            }

            if(isset($this->to) && strtotime($this->to) <= strtotime($tour["enddate"])){

                continue;

            }

            $tourIds[] = $tour["id"];

            $item = ["tour" => $tour];

            $result["tours"][$tour["id"]] = $item;
        }

        foreach ($offers as $offer) {

            if (in_array($offer["tourid"], $tourIds)) {

                $rooms = $offer["rooms"];

                $amount = $offer["amount"];

                $rooms = array_filter($rooms, function ($item) {
                    return !empty($item);
                });

                $result["tours"][$offer["tourid"]]["amount_places"] =
                    ($result["tours"][$offer["tourid"]]["amount_places"] ?? 0) + $amount;

                $result["tours"][$offer["tourid"]]["rooms"] =
                    ($result["tours"][$offer["tourid"]]["rooms"] ?? 0) + count($rooms);

            }

        }

        return $result ?? null;
    }
}
