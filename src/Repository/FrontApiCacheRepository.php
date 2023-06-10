<?php

namespace Selena\Repository;

use Psr\Cache\InvalidArgumentException;
use Selena\Resources\Front\FrontApi;
use Selena\SelenaService;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class FrontApiCacheRepository extends AbstractCacheRepository
{
    /**
     * @var int
     */
    protected int $defaultLifetimes = 60 * 60;

    /**
     * @var FrontApi
     */
    protected FrontApi $frontApi;

    public function __construct(AbstractAdapter $adapter)
    {
        parent::__construct($adapter);

        $this->frontApi = SelenaService::instance()->get(FrontApi::class);
    }

    /**
     * @param int $object_id
     * @return array|mixed
     */
    public function unitList(int $object_id)
    {
        $tag = "unitList_$object_id";

        $callable = $this->frontApi->unitList(["objectid" => $object_id])["units"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes["unitList"]);
    }

    /**
     * @param int $object_id
     * @return array|mixed
     */
    public function tourPackList(int $object_id)
    {
        $tag = "tourPackList_$object_id";

        $callable = fn() => $this->frontApi->tourPackList(["objectid" => $object_id])["tourpacks"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes["serviceList"]);
    }

    /**
     * @param int $object_id
     * @return mixed
     */
    public function serviceList(int $object_id)
    {
        $tag = "serviceList_$object_id";

        $callable = fn() => $this->frontApi->serviceList(["objectid" => $object_id])["services"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes["serviceList"]);
    }

    /**
     * @param int $tour_id
     * @return mixed
     */
    public function tourStandList(int $tour_id)
    {
        $tag = "tourStandList_$tour_id";

        $callable = fn() => $this->frontApi->tourStandList(["tourid" => $tour_id])["tourstands"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes["tourStandList"]);
    }

    /**
     * @param int $object_id
     * @param int|null $tour_id
     * @return mixed
     */
    public function tourList(int $object_id, ?int $tour_id = null)
    {
        $tag = "tourList_$object_id";

        $callable = fn() => $this->frontApi->tourList(["objectid" => $object_id])["tours"] ?? [];

        $tours = $this->process($tag, $callable, $this->cacheLifetimes["tourList"]);

        if (isset($tour_id)) {

            $tours = array_filter($tours, fn($item) => ($item["id"] ?? "") == $tour_id);

        }

        return $tours;
    }

    /**
     * @param int $object_id
     * @param int|null $apartment_id
     * @return array|mixed
     */
    public function apartmentList(int $object_id, ?int $apartment_id = null)
    {
        $tag = "apartmentList_$object_id";

        $callable = fn() => $this->frontApi->apartmentList(["objectid" => $object_id])["apartments"] ?? [];

        $apartments = $this->process($tag, $callable, $this->cacheLifetimes["apartmentList"]);

        if (isset($apartment_id)) {

            $apartments = array_filter($apartments, fn($item) => $item["id"] == $apartment_id);

        }

        return $apartments;
    }

    /**
     * @param int $apartment_id
     * @return mixed
     */
    public function roomList(int $apartment_id)
    {
        $tag = "roomList_$apartment_id";

        $callable = fn() => $this->frontApi->roomList(["apartmentid" => $apartment_id])["rooms"] ?? [];

        $rooms = $this->process($tag, $callable, $this->cacheLifetimes["roomList"]);

        $result = array_filter($rooms, fn($item) => !empty($item));

        return $result;
    }

    /**
     * @param int $apartment_id
     * @param null|int $tour_id
     * @return mixed
     */
    public function apartmentPrices(int $apartment_id, ?int $tour_id = null)
    {
        $tag = "apartmentPrice_{$apartment_id}";

        $callable = fn() => $this->frontApi->apartmentPrice(["apartmentid" => $apartment_id])["apartmentprices"] ?? [];

        $prices = $this->process($tag, $callable, $this->cacheLifetimes["apartmentPrice"]);

        if (isset($tour_id)) {

            $prices = array_filter($prices, fn($item) => ($item["tourid"] ?? "") == $tour_id);

        }

        return $prices;
    }

    /**
     * @param int $object_id
     * @param int|null $tour_id
     * @return mixed
     */
    public function offers(int $object_id, ?int $tour_id = null)
    {
        $tag = "offers_{$object_id}";

        $callable = fn() => $this->frontApi->offers(["objectid" => $object_id])["offers"] ?? [];

        $offers = $this->process($tag, $callable, $this->cacheLifetimes["offers"]);

        if (isset($tour_id)) {

            $offers = array_filter($offers, fn($item) => ($item["tourid"] ?? "") == $tour_id);

        }

        return array_map(function ($item) {

            $item["rooms"] = array_filter($item["rooms"] ?? [], fn($item) => !empty($item));

            return $item;

        }, $offers);
    }

    /**
     * @param int $object_id
     * @return array|mixed
     */
    public function discountList(int $object_id)
    {
        $tag = "offers_{$object_id}";

        $callable = fn() => $this->frontApi->discountList(["objectid" => $this->objectid])["discounts"] ?? [];;

        return $this->process($tag, $callable, $this->cacheLifetimes["offers"]);
    }

    /**
     * Process cache
     *
     * @param $tag
     * @param $callable
     * @param int|null $cacheTime
     * @return mixed
     */
    protected function process($tag, $callable, ?int $cacheTime = null)
    {
        return $this->cachePool->get($tag, function (ItemInterface $item) use ($callable, $cacheTime) {

            $item->expiresAfter($cacheTime ?? $this->defaultLifetimes);

            return $callable();

        });
    }
}
