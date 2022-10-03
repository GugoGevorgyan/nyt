<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Order;

use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;
use Src\Models\Order\CompletedOrder;

/**
 * Interface OrderCrudContract
 * @package Src\Services\Order
 */
interface OrderCrudContract extends BaseContract
{
    /**
     * @param $order_id
     * @param $driver_id
     */
    public function orderAttachToDriver($order_id, $driver_id, int $tik_time = 11): bool;

    /**
     * @param $data
     * @return Collection
     */
    public function orderDataFilter($data): Collection;

    /**
     * @param  array  $data
     * @return mixed
     */
    public function companyClientOrderFilter(array $data);

    /**
     * @param  array  $form_data
     * @return mixed
     */
    public function createCallCenterOrder(array $form_data);

    /**
     * @param  int  $driver_id
     * @param  null  $take
     * @param  null  $skip
     * @return Collection
     */
    public function getDriverOrdersList(int $driver_id, $take = null, $skip = null): Collection;

    /**
     * @param $completed_order_id
     * @return CompletedOrder
     */
    public function getDriverOrderTrajectory(int $completed_order_id): CompletedOrder;

    /**
     * @param Collection $order_data
     * @param bool $create_adds
     * @return mixed
     */
    public function createOrder(Collection $order_data, bool $create_adds = true);

    /**
     * @param  array  $timeData
     * @return array
     */
    public function compareTimeWithPreOrderTime(array $timeData): array;
}
