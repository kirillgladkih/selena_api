<?php

namespace Selena\Resources\Booking;

use Selena\Resources\BasicApi;
/**
 * Booking api
 * 
 * Documentation: https://selena-online.ru/rest/documentation/bookingapi
 */
class BookingApi extends BasicApi
{
    /**
     * Бронирование
     * 
     * class that implements the query: \Selena\Resources\Booking\Queries\ReserveQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function reserve(array $data = [], ?\Closure $responder = null): mixed
    {
        $query = new \Selena\Resources\Booking\Queries\ReserveQuery($data);

        return $this->respond($query, $responder);
    }
    /**
     * Ануляция заказа
     *
     * class that implements the query: \Selena\Resources\Booking\Queries\CancelOrderQuery
     * 
     * @param array $data
     * @param \Closure|null $responder
     * @return mixed
     */
    public function cancelOrder(array $data = [], ?\Closure $responder = null): mixed
    {
        $query = new \Selena\Resources\Booking\Queries\CancelOrderQuery($data);

        return $this->respond($query, $responder);
    }
}
