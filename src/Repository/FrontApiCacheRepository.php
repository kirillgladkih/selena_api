<?php

namespace Selena\Repository;

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
        $tag = CacheTags::UNIT_LIST_TAG . "_$object_id";

        $callable = fn() => $this->frontApi->unitList(["objectid" => $object_id])["units"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::UNIT_LIST_TAG]);
    }

    /**
     * @param int $object_id
     * @return array|mixed
     */
    public function tourPackList(int $object_id)
    {
        $tag = CacheTags::TOUR_PACK_LIST_TAG . "_$object_id";

        $callable = fn() => $this->frontApi->tourPackList(["objectid" => $object_id])["tourpacks"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::TOUR_PACK_LIST_TAG]);
    }

    /**
     * @param int $object_id
     * @return mixed
     */
    public function serviceList(int $object_id)
    {
        $tag =  CacheTags::SERVICE_LIST_TAG . "_$object_id";

        $callable = fn() => $this->frontApi->serviceList(["objectid" => $object_id])["services"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::SERVICE_LIST_TAG]);
    }

    /**
     * @param int $tour_id
     * @return mixed
     */
    public function tourStandList(int $tour_id)
    {
        $tag = CacheTags::TOUR_STAND_LIST_TAG . "_$tour_id";

        $callable = fn() => $this->frontApi->tourStandList(["tourid" => $tour_id])["tourstands"] ?? [];

        return $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::TOUR_STAND_LIST_TAG]);
    }

    /**
     * @param int $object_id
     * @param int|null $tour_id
     * @return mixed
     */
    public function tourList(int $object_id, ?int $tour_id = null)
    {
        $tag = CacheTags::TOUR_LIST_TAG . "_$object_id";

        $callable = fn() => $this->frontApi->tourList(["objectid" => $object_id])["tours"] ?? [];

        $tours = $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::TOUR_LIST_TAG]);

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
        $tag = CacheTags::APARTMENT_LIST_TAG . "_$object_id";

        $callable = fn() => $this->frontApi->apartmentList(["objectid" => $object_id])["apartments"] ?? [];

        $apartments = $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::APARTMENT_LIST_TAG]);

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
        $tag = CacheTags::ROOM_LIST_TAG . "_$apartment_id";

        $callable = fn() => $this->frontApi->roomList(["apartmentid" => $apartment_id])["rooms"] ?? [];

        $rooms = $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::ROOM_LIST_TAG]);

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
        $tag = CacheTags::APARTMENT_PRICE_TAG . "_{$apartment_id}";

        $callable = fn() => $this->frontApi->apartmentPrice(["apartmentid" => $apartment_id])["apartmentprices"] ?? [];

        $prices = $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::APARTMENT_PRICE_TAG]);

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
        $tag = CacheTags::OFFERS_TAG . "_{$object_id}";

        $callable = fn() => $this->frontApi->offers(["objectid" => $object_id])["offers"] ?? [];

        $offers = $this->process($tag, $callable, $this->cacheLifetimes[CacheTags::OFFERS_TAG]);

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
        $tag =  CacheTags::DISCOUNT_LIST_TAG . "_{$object_id}";

        $callable = fn() => $this->frontApi->discountList(["objectid" => $this->objectid])["discounts"] ?? [];;

        return $this->process($tag, $callable, CacheTags::DISCOUNT_LIST_TAG);
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
