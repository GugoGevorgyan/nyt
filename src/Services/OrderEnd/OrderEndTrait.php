<?php

declare(strict_types=1);

namespace Src\Services\OrderEnd;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Src\Broadcasting\Broadcast\Driver\ClientOrderCancel;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\Order;
use Src\Models\Order\OrderShippedStatus;

/**
 *
 */
trait OrderEndTrait
{

    /**
     * @param $order_id
     * @param $from_cords
     * @return int|float
     */
    protected function detectCancelFee($order_id, $from_cords): float|int
    {
        $process = $this->processContract
            ->whereHas('order', fn($query) => $query->where('orders.order_id', '=', $order_id))
            ->findFirst(['order_process_id', 'order_shipped_id', 'cancel_price']);

        if ($process) {
            return $process->cancel_price;
        }

        $price = 0;

        $tariffs = $this->tariffContract->getTariffByOrder($order_id);

        switch ($tariffs) {
            case !empty($tariffs['tariff_behind']):
                $in_polygon = $this->geoService->pointInPolygon($tariffs['area'], $from_cords);

                if ($in_polygon && $tariffs['tariff_region']['cancel_fee']) {
                    $price = $tariffs['tariff_region']['cancel_fee'];
                }

                if (!$in_polygon && $tariffs['tariff_behind']['cancel_fee']) {
                    $price = $tariffs['tariff_behind']['cancel_fee'];
                }
                break;
            case !empty($tariffs['tariff_destination']):
                if ($tariffs['tariff_destination']['cancel_fee']) {
                    $price = $tariffs['tariff_destination']['cancel_fee'];
                }
                break;
            case !empty($tariffs['tariff_region']):
                if ($tariffs['tariff_region']['cancel_fee']) {
                    $price = $tariffs['tariff_region']['cancel_fee'];
                }
                break;
            default:
        }
        // @todo rent tariff

        return $price;
    }

    /**
     * @param $order
     * @param $client
     * @param $driver
     * @param  array  $cancel_fee
     * @return array{create_aborted_order: int|string, aborted_options: array}
     * @throws Exception
     */
    protected function cancelCreated($order, $client, $driver, array $cancel_fee = []): array
    {
        $create_aborted_order = $this->clientOrderEndStatuses($order, $client);

        if ($driver) {
            $this->driverStatusesClientCancelOrder($driver, $order, $cancel_fee);
        }

        $this->removeRedisKeys($order->order_id, $client->client_id);

        $aborted_options = $client->canceledOrderFeedback()->get(['option', 'name']);

        $order->preorder
            ? $order->ordering_shipments->flatMap(fn($shipped) => $shipped
            ->update([
                'current' => false,
                'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_CANCELED)
            ]))
            : null;


        return [$create_aborted_order, $aborted_options];
    }

    /**
     * @param  Order  $order
     * @param  Model  $client
     * @return int|null
     * @throws Exception
     */
    protected function clientOrderEndStatuses(Order $order, Model $client): ?int
    {
        $this->orderContract->beginTransaction();
        $this->orderContract->forgetCache();

        if (!$this->orderContract->update($order->order_id, ['status_id' => Order::ORDER_STATUS_CANCELED])) {
            $this->orderContract->rollBack();
            return null;
        }

        $create_aborted_order = $this->canceledOrderContract->create([
            'order_id' => $order->order_id,
            'cancelable_id' => $client->client_id,
            'cancelable_type' => $client->getMap()
        ]);

        if (!$create_aborted_order) {
            $this->orderContract->rollBack();
            return null;
        }

        if (!$this->clientContract->update($client->{$client->getKeyName()}, ['in_order' => 0])) {
            $this->orderContract->rollBack();
            return null;
        }

        $this->orderContract->commit();

        return (int)$create_aborted_order->canceled_order_id;
    }

    /**
     * @param  Driver  $driver
     * @param  Order  $order
     * @param $cancel_fee
     * @return bool|null
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function driverStatusesClientCancelOrder(Driver $driver, Order $order, $cancel_fee): ?bool
    {
        $this->orderContract->beginTransaction();
        $this->orderContract->forgetCache();

        ClientOrderCancel::broadcast($driver, $order, $cancel_fee);

        if (!$this->driverContract->update($driver->driver_id,
            ['current_status_id' => DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE)])) {
            $this->orderContract->rollBack();
            return false;
        }

        $shipped_ids = $order->ordering_shipments ? $order->ordering_shipments->map(fn($item
        ) => $item->order_shipped_driver_id)->all() : [];
        $shipped_update = $this->shippedContract->whereIn('order_shipped_driver_id', $shipped_ids)->updateSet([
            'status_id' => OrderShippedStatus::PRE_CANCELED,
            'current' => 0
        ]);

        if ($shipped_ids && !$shipped_update) {
            $this->orderContract->rollBack();
            return false;
        }

        $this->orderContract->commit();
        return true;
    }

    /**
     * @param  object  $payload
     * @param  array|null  $crossing
     * @param  array  $cords
     * @param $price
     * @param $tariff_id
     * @return object|null
     * @throws Exception
     */
    protected function createCompleted(object $payload, ?array $crossing, array $cords, $price, $tariff_id): ?object
    {
        $address = $this->geoService->fullAddressGeocode($cords['end']['lat'], $cords['end']['lut']);
        $distance_duration_price = $this->distanceDurationPrice($tariff_id, $payload, $crossing);

        $cross_data = $crossing ? [
            'in_price' => $crossing['in_price'],
            'in_distance_price' => $crossing['in_distance_price'],
            'in_duration_price' => $crossing['in_duration_price'],
            'out_price' => $crossing['out_price'],
            'out_distance_price' => $crossing['out_distance_price'],
            'out_duration_price' => $crossing['out_duration_price'],
            'in_distance' => $crossing['in_distance'],
            'out_distance' => $crossing['out_distance'],
            'in_duration' => $crossing['in_duration']['time'],
            'out_duration' => $crossing['out_duration']['time'],
            'in_trajectory' => $crossing['in_trajectory'],
            'out_trajectory' => $crossing['out_trajectory'],
        ] : [];

        $default_data = [
            'order_id' => $payload->order->order_id,
            'driver_id' => $payload->driver->driver_id,
            'car_id' => $payload->driver->car_id,
            'destination_address' => $address['full_address'],
            'destination_lat' => $cords['end']['lat'],
            'destination_lut' => $cords['end']['lut'],
            'distance' => round_d($payload->process->distance_traveled), // ☺♠♠♠♠
            'duration' => round_t($payload->process->travel_time),
            'distance_price' => $distance_duration_price['distance'],
            'duration_price' => $distance_duration_price['duration'],
            'trajectory' => $payload->in_process_road->real_road,
            'cost' => $price['price']
        ];

        if ($this->completedContract->where('order_id', '=', $payload->order->order_id)->exists('order_id')) {
            return null;
        }

        return $this->orderContract->beginTransaction(function () use ($cross_data, $default_data) {
            $this->orderCrossingContract->forgetCache();

            $create_completed = $this->completedContract->create($default_data);

            if ($cross_data && $create_completed) {
                $cross_data['completed_id'] = $create_completed->{$create_completed->getKeyName()};
                $this->orderCrossingContract->create($cross_data);
            }

            return $create_completed;
        });
    }

    /**
     * @param $client_id
     * @return array|null
     */
    protected function getClientCancelPayload($client_id): ?array
    {
        $client = $this->clientContract
            ->with([
                'current_order' => fn(HasOne $order_query) => $order_query->select([
                    'order_id',
                    'status_id',
                    'client_id'
                ])
            ])
            ->find($client_id, [$this->clientContract->getKeyName(), 'phone']);

        if (!$client || !$client->current_order) {
            return null;
        }

        $order = $this->orderContract
            ->with([
                'preorder' => fn($query) => $query->select(['preorder_id', 'order_id']),
                'ordering_shipments' => fn($query) => $query->select([
                    'order_shipped_driver_id',
                    'driver_id',
                    'order_id'
                ])
            ])
            ->find($client->current_order->order_id);

        $driver = $this->orderService->getOrderDriver($client->current_order->order_id);

        return [$client, $driver, $order];
    }

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @param  int  $serial
     * @return bool
     */
    protected function createOrderSlip(int $order_id, int $driver_id, int $serial): bool
    {
        if (!$this->orderContract->whereExistsExist('order_id', '=', $order_id, 'order_id')) {
            return false;
        }

        if (!$this->orderCorporateContract->where('order_id', '=', $order_id)->updateSet([
            'driver_id' => $driver_id,
            'slip_number' => $serial
        ])) {
            return false;
        }

        return true;
    }
}
