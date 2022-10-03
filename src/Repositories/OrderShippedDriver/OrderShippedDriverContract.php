<?php

declare(strict_types=1);


namespace Src\Repositories\OrderShippedDriver;


use Repository\Contracts\BaseRepositoryContract;
use Src\Models\Order\OrderShippedDriver;

/**
 * Interface OrderShippedDriverContract
 * @package Src\Repositories\PreOrderData
 */
interface OrderShippedDriverContract extends BaseRepositoryContract
{
    /**
     * @param $order_id
     * @return bool
     */
    public function isOrderPassed($order_id): bool;

    /**
     * @param $order_id
     * @param $driver_id
     * @return OrderShippedDriver|null
     */
    public function getCurrentPendingShipped($order_id, $driver_id): ?OrderShippedDriver;
}
