<?php

namespace Selena\Resources\Front;

use Selena\Resources\BasicApi;

class FrontApi extends BasicApi
{
    /**
     * Получить список категорий номеров (кают)
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ApartmentListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function apartmentList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\ApartmentListQuery::class, $data);
    }
    /**
     * Получить цены на размещение (проживание)
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ApartmentPriceQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function apartmentPrice(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\ApartmentPriceQuery::class, $data);
    }
    /**
     * Получить список городов и административных муниципальных образований
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\CityListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function cityList(array $data): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\CityListQuery::class, $data);
    }
    /**
     * Получить список направлений туризма
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\DirectionListQuery
     *  
     * @param array $data
     * @return mixed
     */
    public function directionList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\DirectionListQuery::class, $data);
    }
    /**
     * Получить социальные льготы
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\DiscountLIstQuery
     *  
     * @param array $data
     * @return mixed
     */
    public function discountList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\DiscountLIstQuery::class, $data);
    }
    /**
     * Получить список поддерживаемых языков
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\LanguagesQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function languages(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\LanguagesQuery::class, $data);
    }
    /**
     * Получить список объектов размещения 
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ObjectListQuery
     * 
     * @param array $data = []
     * @return mixed
     */
    public function objectList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\ObjectListQuery::class, $data);
    }
    /**
     * Получить наличие мест
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\OffersQuery::class
     * 
     * @param array $data
     * @return mixed
     */
    public function offers(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\OffersQuery::class, $data);
    }
    /**
     * Получить список регионов
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\RegionListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function regionList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\RegionListQuery::class, $data);
    }
    /**
     * Получить список номеров (кают)
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\RoomListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function roomList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\RoomListQuery::class, $data);
    }
    /**
     * Получить пол туристов в забронированных заказах
     *
     * class that implements the query: \Selena\Resources\Front\Queries\RoomOccupiedSexQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function roomOccupiedSex(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\RoomOccupiedSexQuery::class, $data);
    }
    /**
     * Получить спиоск групп услуг
     *
     * class that implements the query: \Selena\Resources\Front\Queries\ServiceGroupListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function serviceGroupList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\ServiceGroupListQuery::class, $data);
    }
    /**
     * Получить список услуг
     * 
     * class that implements the query: \Selena\Resources\Front\Queries\ServiceListQuery
     *
     * @param array $data
     * @return mixed
     */
    public function serviceList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\ServiceListQuery::class, $data);
    }
    /**
     * Получить цены на услугу
     *
     * class that implements the query: \Selena\Resources\Front\Queries\ServicePriceQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function servicePrice(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\ServicePriceQuery::class, $data);
    }
    /**
     * Получить список направлений туров (круизов, маршрутов)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourDirectionListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function tourDirectionList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\TourDirectionListQuery::class, $data);
    }
    /**
     * Получить cписок туров (круизов, маршрутов)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function tourList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\TourListQuery::class, $data);
    }
    /**
     * Получить список видов путёвок
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourPackListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function tourPackList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\TourPackListQuery::class, $data);
    }
    /**
     * Получить программу тура
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TourStandListQuery::class
     * 
     * @param array $data
     * @return mixed
     */
    public function tourStandList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\TourStandListQuery::class, $data);
    }
    /**
     * Получить список видов транспорта для перевозки в рамках тура
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TransportListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function transportList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\TransportListQuery::class, $data);
    }
    /**
     * Получить список пунктов посадки/высадки пассажиров в рейсах
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TransportListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function transportPointList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\TransportPointListQuery::class, $data);
    }
    /**
     * Получить список рейсов (перевозка в рамках тура)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\TripListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function tripListQuery(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\TripListQuery::class, $data);
    }
    /**
     * Получить список корпусов (палуб, для экскурсионных туров - гостиниц)
     *
     * class that implements the query: \Selena\Resources\Front\Queries\UnitListQuery
     * 
     * @param array $data
     * @return mixed
     */
    public function unitList(array $data = []): mixed
    {
        return $this->resolveQuery(\Selena\Resources\Front\Queries\UnitListQuery::class, $data);
    }
    /**
     * Resolve query
     *
     * @param string $query
     * @param array $data
     * @return mixed
     */
    protected function resolveQuery(string $query, array $data = []): mixed
    {
        $query = new $query($data);

        $response = $query->resolve($this->client);

        $result = $response->getBody()->getContents();

        return $result;
    }
}
