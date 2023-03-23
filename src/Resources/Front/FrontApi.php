<?php

namespace Selena\Resources\Front;

use Selena\Resources\BasicApi;
/**
 * Front api 
 * 
 * Documentation: https://selena-online.ru/rest/documentation/frontapi
 */
class FrontApi extends BasicApi
{
    /**
     * Получить список категорий номеров (кают)
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ApartmentListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function apartmentList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\ApartmentListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить цены на размещение (проживание)
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ApartmentPriceQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function apartmentPrice(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\ApartmentPriceQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список городов и административных муниципальных образований
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\CityListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function cityList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\CityListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список направлений туризма
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\DirectionListQuery
     *  
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function directionList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\DirectionListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить социальные льготы
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\DiscountLIstQuery
     *  
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function discountList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\DiscountListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список поддерживаемых языков
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\LanguagesQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function languages(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\LanguagesQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список объектов размещения 
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ObjectListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function objectList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\ObjectListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить наличие мест
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\OffersQuery::class
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function offers(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\OffersQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список регионов
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\RegionListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function regionList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\RegionListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список номеров (кают)
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\RoomListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function roomList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\RoomListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить пол туристов в забронированных заказах
     *
     * class that implements the query: \Selena\Resources\Front\Queries\RoomOccupiedSexQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function roomOccupiedSex(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\RoomOccupiedSexQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить спиоск групп услуг
     *
     * class that implements the query: \Selena\Resources\Front\Queries\ServiceGroupListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function serviceGroupList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\ServiceGroupListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список услуг
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ServiceListQuery
     *
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function serviceList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\ServiceListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить цены на услугу
     *
     * class that implements the query: \Selena\Resources\Front\Queries\ServicePriceQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function servicePrice(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\ServicePriceQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список направлений туров (круизов, маршрутов)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourDirectionListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function tourDirectionList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\TourDirectionListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить cписок туров (круизов, маршрутов)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function tourList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\TourListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список видов путёвок
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourPackListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function tourPackList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\TourPackListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить программу тура
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourStandListQuery::class
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function tourStandList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\TourStandListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список видов транспорта для перевозки в рамках тура
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TransportListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function transportList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\TransportListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список пунктов посадки/высадки пассажиров в рейсах
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TransportListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function transportPointList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\TransportPointListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список рейсов (перевозка в рамках тура)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TripListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function tripListQuery(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\TripListQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Получить список корпусов (палуб, для экскурсионных туров - гостиниц)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\UnitListQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function unitList(array $data = [], ?\Closure $responder = null)
    {
        $query = new \Selena\Resources\Front\Queries\UnitListQuery($data);

        return $this->respond($query, $responder);
    }
}
