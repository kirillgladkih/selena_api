<?php

namespace Selena\Tasks\Reserving;

use Psr\Http\Client\ClientInterface;
use Selena\Dto\Reserving\Order;
use Selena\Dto\Reserving\Tourist;
use Selena\Exceptions\ApiException;
use Selena\Resources\Booking\BookingApi;
use Selena\Tasks\TaskContract;

/**
 * Бронирование
 */
class ReserveTask implements TaskContract
{
    /**
     * Order
     *
     * @var Order
     */
    protected Order $order;
    /**
     * Tourists
     *
     * @var Tourist[]
     */
    protected array $tourists;
    /**
     * Commit
     *
     * @var boolean
     */
    protected bool $commit;
    /**
     * Бронирование
     *
     * @param Order $order
     * @param Tourist[] $tourists
     * @param boolean $commit
     */
    public function __construct(Order $order, array $tourists, bool $commit = false)
    {
        $this->tourists = $tourists;

        $this->order = $order;

        $this->commit = $commit;
    }
    /**
     * Get tag name for cache
     *
     * @return string
     */
    public function tag(): string
    {
        return self::class;
    }
    /**
     * Get callable
     *
     * @return callable
     */
    public function get(): callable
    {
        return function (ClientInterface $client) {

            try {

                $bookingApi = new BookingApi($client);

                foreach ($this->tourists as $tourist) $tourists[] = $tourist->toArray();

                $data = [
                    "commit" => $this->commit,
                    "order" => $this->order->toArray(),
                    "tourists" => $tourists ?? []
                ];

                $result = $bookingApi->reserve($data);

            } catch (ApiException $exception) {

                $result = null;
            }

            return $result;
        };
    }
}
