<?php

declare(strict_types=1);


namespace Src\Services\Order;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;
use Src\Exceptions\Order\OrderCanceledInSearchDriverException;
use Src\Models\Driver\Driver;

/**
 * Interface OrderServiceContract
 * @package Src\Services\Order
 */
interface OrderServiceContract extends BaseContract
{

    /**
     * @param $id
     * @return mixed
     */
    public function getOrderInfo($id): array;

    /**
     * @param  Model  $client
     * @param  array  $coordinates
     * @param  array  $options
     * @param  array  $times
     * @param  bool  $single
     * @return array|null
     */
    public function orderFromToPrice(Model $client, array $coordinates = [], array $options = [], array $times = []): ?array;

    /**
     * @param $tariffs
     * @param  array  $price_data
     * @param  Model  $client
     * @param $coordinates
     * @param  null  $order_id
     */
    public function setInitialOrderData($tariffs, array $price_data, Model $client, $coordinates, $order_id = null): void;

    /**
     * @param  Model  $client
     * @param  array  $cord
     * @param  array  $options
     * @param  array  $times
     * @param  bool  $rent
     * @return array|null
     */
    public function orderFromToPrices(Model $client, array $cord = [], array $options = [], array $times = [], bool $rent = false): ?array;

    /**
     * @param $driver_id
     * @param $pause_hash
     * @return string
     */
    public function driverOrderPause($driver_id, $pause_hash): int;

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function callCenterOrderPaginate($request): LengthAwarePaginator;

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function callCenterDispatcherOrderPaginate($request): LengthAwarePaginator;

    /**
     * @param $client_id
     * @return Collection
     */
    public function getLastOrders($client_id): Collection;

    /**
     * @return array|Collection
     */
    public function getOrderTypes(): array|Collection;

    /**
     * @return Collection
     */
    public function getOrderStatuses(): Collection;

    /**
     * @return array|Collection
     */
    public function getPaymentTypes(): array|Collection;

    /**
     * @return Collection
     */
    public function getCarOptions(): Collection;

    /**
     * @return array|Collection
     */
    public function getCarClasses(): array|Collection;

    /**
     * @param $shipment_driver_id
     * @param  array  $routes
     * @param  bool  $on_way
     * @return array|null
     */
    public function createOrderProcessRoutes($shipment_driver_id, array $routes, bool $on_way = false): ?array;

    /**
     * @param $order_id
     * @param  array  $values
     * @return null|Driver
     */
    public function getOrderDriver($order_id, array $values = ['*']): ?Driver;

    /**
     * @return Collection
     */
    public function operatorPendingOrders(): Collection;

    /**
     * @return Collection
     */
    public function dispatcherPendingOrders(): Collection;

    /**
     * @param $order_id
     * @param  bool  $response
     * @return bool
     * @throws OrderCanceledInSearchDriverException
     */
    public function orderHasCanceled($order_id, bool $response = false): bool;

    /**
     * @param $stage
     * @param $values
     * @param $order_id
     * @param $date_name
     * @return bool
     */
    public function setStages($stage, $values, $order_id, $date_name): bool;

    /**
     * @param $driver_id
     * @param $order_id
     * @param $from
     * @param  null  $distance
     * @param  null  $duration
     * @param  array  $points
     * @return array
     */
    public function createOrderForDriver($driver_id, $order_id, $from, $distance = null, $duration = null, array $points = []): array;

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  bool  $current
     * @return array|null
     */
    public function createDriverCommonList(int $driver_id, int $order_id, bool $current = false): ?array;

    /**
     * @param $order_id
     * @return object|null
     */
    public function getOrderForOperator($order_id): ?object;

    /**
     * @param $order_id
     * @return null|object
     */
    public function getOrderForDispatcher($order_id): null|object;

    /**
     * @param $order_id
     * @return object|null
     */
    public function orderHistory($order_id): ?object;

    /**
     * @param $request
     * @return mixed
     */
    public function slipUpdate($request): bool;

    /**
     * @param  int  $order_id
     * @param  string  $comment
     * @param  bool  $for_driver
     * @return bool|Collection
     */
    public function orderCommentCreate(int $order_id, string $comment, bool $for_driver = false): bool|Collection;

    /**
     * @param $request
     * @return array|null
     */
    public function callCenterOrderFeedbackCreate($request): ?array;

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @return mixed
     */
    public function prepareCommonOrder(int $order_id, int $driver_id): ?array;

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @return bool
     */
    public function commonOrderAcceptStatuses(int $order_id, int $driver_id): bool;

    /**
     * @param $driver_id
     * @return array
     */
    public function getWaybillDistanceWithPrice($driver_id): array;

    /**
     * @param  array  $order_data
     * @param $order
     * @param  array|null  $price_data
     * @return Collection
     */
    public function afterOrderCreate(array $order_data, $order, array $price_data = null): Collection;

    /**
     * @param $order_id
     * @return bool
     */
    public function repeatOrder($order_id): bool;

    /**
     * @param $order_id
     * @return mixed
     */
    public function viewOrderInfo($order_id): array;

    /**
     * @param int $order_id
     * @return mixed
     */
    public function setRepeatedAt(int $order_id);
}
