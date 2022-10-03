<?php

declare(strict_types=1);


namespace Src\Repositories\OrderShippedDriver;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderShippedDriver;
use Src\Models\Order\OrderShippedStatus;

/**
 * Class OrderShippedDriverRepository
 * @package Src\Repositories\PreOrderData
 */
class OrderShippedDriverRepository extends BaseRepository implements OrderShippedDriverContract
{
    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderShippedDriver::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_shipped_drivers');
    }

    /**
     * @param $order_id
     * @return bool
     */
    public function isOrderPassed($order_id): bool
    {
        return $this
            ->where('order_id', '=', $order_id)
            ->where('current', '=', true)
            ->where('status_id', '=', OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_PENDING))
            ->orWhere('status_id', '=', OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_ACCEPTED))
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function getCurrentPendingShipped($order_id, $driver_id): ?OrderShippedDriver
    {
        return $this
            ->where('driver_id', '=', $driver_id)
            ->where('order_id', '=', $order_id)
            ->where('current', '=', true)
            ->where('status_id', '=', OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_PENDING))
            ->with([
                'estimated_rating' => fn($query) => $query->select([
                    'estimated_rating_id',
                    'order_id',
                    'driver_id',
                    'remove_patterns'
                ])
            ])
            ->findFirst(['order_shipped_driver_id', 'estimated_rating_id']);
    }
}
