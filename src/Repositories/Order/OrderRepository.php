<?php

declare(strict_types=1);


namespace Src\Repositories\Order;


use Exception;
use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\Driver;
use Src\Models\Order\Order;

/**
 * Class Order
 * @package Src\Repositories\Order
 */
class OrderRepository extends BaseRepository implements OrderContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Order::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('orders');
    }

    /**
     * @inheritDoc
     */
    public function getOrderedDriverData($order_id, array $values = ['*']): ?Driver
    {
        $order = $this
            ->with(['driver:drivers.'.implode(',', $values)])
            ->find($order_id, ['order_id']);

        return $order->driver ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getOrderedShippedDriver($order_id, array $values = ['*']): ?Driver
    {
        $order = $this
            ->with(['shipped_driver:drivers.'.implode(',', $values)])
            ->find($order_id, ['order_id']);

        return $order->shipped_driver ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCompletedOrderDriverData($order_id, array $values = ['*']): ?Driver
    {
        $order = $this
            ->with(['completed_driver:drivers.'.implode(',', $values)])
            ->find($order_id, ['order_id']);

        return $order->completed_driver ?? null;
    }

    /**
     * @inheritDoc
     */
    public function orderHistory($order_id): object
    {
        try {
            return $this
                ->with([
                    'ordering_shipments' => fn($query) => $query
                        ->whereDoesntHave('attach')
                        ->with([
                            'status' => fn($query) => $query->select(['order_shipped_status_id', 'status', 'name', 'color', 'text']),
                            'driver_info' => fn($query) => $query->select([
                                'drivers_info.driver_info_id',
                                'driver_id',
                                'name',
                                'surname',
                                'patronymic',
                                'photo'
                            ]),
                        ])
                        ->select([
                            'order_shipped_driver_id',
                            'driver_id',
                            'order_id',
                            'estimated_rating_id',
                            'status_id',
                            'current',
                            'common',
                            'accept_hash',
                            'on_way_hash',
                            'in_place_hash',
                            'in_order_hash',
                            'pause_hash',
                            'end_hash',
                            'late',
                            'created_at'
                        ]),
                    'selected_shipped' => fn($query) => $query
                        ->with([
                            'process' => fn($query) => $query->select([
                                'price',
                                'total_price',
                                'calculate_price',
                                'increment_price',
                                'options_price',
                                'pause_price',
                                'sitting_price',
                                'cancel_price',
                                'waiting_price',
                                'distance_traveled',
                                'travel_time',
                                'waiting_time',
                                'pause_time',
                                'order_shipped_id',
                                'order_process_id',
                            ])
                        ])
                        ->select([
                            'order_shipped_driver_id',
                            'driver_id',
                            'order_id',
                            'estimated_rating_id',
                            'status_id',
                            'current',
                            'common',
                            'accept_hash',
                            'on_way_hash',
                            'in_place_hash',
                            'in_order_hash',
                            'pause_hash',
                            'end_hash',
                            'late',
                            'created_at'
                        ])->with([
                            'driver' => fn($query) => $query->select([
                                'driver_id',
                                'entity_id',
                                'driver_info_id',
                                'current_status_id',
                                'current_franchise_id',
                                'selected_class',
                                'selected_option',
                                'online',
                                'mean_assessment',
                                'rating',
                                'logged',
                                'is_ready',
                                'lat',
                                'lut',
                                'azimuth',
                                'nickname',
                                'device',
                                'password',
                                'phone',
                                'car_id',
                            ]),
                            'status' => fn($query) => $query->select(['order_shipped_status_id', 'status', 'name', 'color', 'text']),
                        ]),
                    'attach' => fn($query) => $query->with([
                        'shipped' => fn($q) => $q->with(['status']),
                        'driver_info' => fn($query) => $query->select(['drivers_info.driver_info_id', 'driver_id', 'name', 'surname', 'patronymic', 'photo'])
                    ]),
                    'stage' => fn($query) => $query->select([
                        'order_stage_cord_id',
                        'order_id',
                        'accept',
                        'accepted',
                        'on_way',
                        'on_wayed',
                        'in_place',
                        'in_placed',
                        'start',
                        'started',
                        'pauses',
                        'end',
                        'ended'
                    ]),
                    'on_way_road' => fn($query) => $query->select(['order_on_way_road_id', 'shipment_driver_id', 'real_road', 'distance', 'duration']),
                    'in_process_road' => fn($query) => $query->select(['order_in_process_road_id', 'shipment_driver_id', 'real_road', 'distance', 'duration']),
                    'crossing' => fn($query) => $query->select([
                        'completed_order_crossing_id',
                        'completed_id',
                        'in_price',
                        'out_price',
                        'in_distance_price',
                        'out_distance_price',
                        'in_duration_price',
                        'out_duration_price',
                        'in_distance',
                        'out_distance',
                        'in_duration',
                        'out_duration',
                        'in_trajectory',
                        'out_trajectory',
                    ]),
                    'completed' => fn($query) => $query
                        ->with([
                            'car' => fn($query) => $query->select(['car_id', 'mark', 'model', 'color', 'year']),
                            'driver_info' => fn($query) => $query->select(['drivers_info.driver_info_id', 'name', 'surname', 'patronymic', 'photo']),
                        ])
                        ->select([
                            'completed_order_id',
                            'order_id',
                            'driver_id',
                            'car_id',
                            'destination_address',
                            'destination_lat',
                            'destination_lut',
                            'cost',
                            'distance',
                            'duration',
                            'duration_price',
                            'duration_price',
                        ]),
                    'feedbacks' => fn($query) => $query
                        ->select([
                            'order_feedback_id',
                            'feedback_option_id',
                            'order_id',
                            'orderable_id',
                            'orderable_type',
                            'writable_id',
                            'writable_type',
                            'readable_id',
                            'readable_type',
                            'text',
                            'assessment'
                        ])
                        ->with(['option', 'writable', 'readable']),
                    'complaints' => fn($query) => $query
                        ->with([
                            'recipient' => fn($query) => $query->select(['system_worker_id', 'name', 'surname', 'patronymic']),
                            'writer' => fn($query) => $query->select(['system_worker_id', 'name', 'surname', 'patronymic']),
                        ])
                        ->select(['complaint_id', 'order_id', 'writer_id', 'recipient_id', 'status_id', 'subject', 'complaint']),
                    'worker_comments' => fn($query) => $query
                        ->with(['worker' => fn($query) => $query->select(['system_worker_id', 'name', 'surname', 'patronymic'])])
                        ->select(['order_worker_comment_id', 'order_id', 'system_worker_id', 'text']),
                    'car_options' => fn($query) => $query->select(['car_option_id', 'option', 'price', 'name']),
                    'corporate' => fn($query) => $query->select(['order_corporate_id', 'order_id', 'company_id', 'driver_id', 'slip_number']),
                    'initial_data' => fn($query) => $query->select(['order_initial_data_id', 'order_id', 'initial_tariff_id', 'price']),
                    'tariff' => fn($query) => $query
                        ->with(['current_tariff'])
                        ->select([
                            'tariff_id',
                            'tariff_type_id',
                            'car_class_id',
                            'payment_type_id',
                            'country_id',
                            'region',
                            'city',
                            'tariffable_id',
                            'tariffable_type',
                            'name',
                            'minimal_price'
                        ]),
                ])
                ->find($order_id,
                    [
                        'order_id',
                        'customer_id',
                        'status_id',
                        'customer_type',
                        'client_id',
                        'car_option',
                        'address_from',
                        'address_to',
                        'from_coordinates',
                        'to_coordinates'
                    ]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()]);
        }
    }
}
