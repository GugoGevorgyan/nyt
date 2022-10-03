<?php

declare(strict_types=1);


namespace Src\Repositories\Order;


use Repository\Contracts\BaseRepositoryContract;
use Src\Models\Driver\Driver;

/**
 * Class OrderContract
 * @package Src\Repositories\Order
 */
interface OrderContract extends BaseRepositoryContract
{
    /**
     * @param $order_id
     * @param  array  $values
     * @return Driver|null
     */
    public function getOrderedDriverData($order_id, array $values = []): ?Driver;

    /**
     * @param $order_id
     * @param  array|string[]  $values
     * @return Driver|null
     */
    public function getOrderedShippedDriver($order_id, array $values = ['*']): ?Driver;

    /**
     * @param $order_id
     * @param  array|string[]  $values
     * @return Driver|null
     */
    public function getCompletedOrderDriverData($order_id, array $values = ['*']): ?Driver;

    /**
     * @param $order_id
     * @return object
     */
    public function orderHistory($order_id): object;
}
