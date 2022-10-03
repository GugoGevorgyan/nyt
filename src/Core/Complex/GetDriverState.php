<?php

declare(strict_types=1);


namespace Src\Core\Complex;


use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Traits\Complex;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\OrderCorporate\OrderCorporateContract;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * Class GetDriverState
 * @method static complex(object $driver)
 * @package Src\Core\Complex
 * @property OrderCorporateContract $orderCorporateContract
 * @property DriverContract $driverContract
 */
final class GetDriverState
{
    use Complex;

    /**
     * GetDriverState constructor.
     * @param  Driver  $driver
     */
    public function __construct(protected Driver $driver)
    {
    }

    /**
     * @param  OrderCorporateContract  $orderCorporateContract
     * @return array|null
     * @noinspection MultipleReturnStatementsInspection
     */
    public function handle(OrderCorporateContract $orderCorporateContract): ?array
    {
        $locked = $this->lockedWithLeft();

        return match ($this->driver->current_status_id) {
            DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE) => $this->listNumberFreeState($locked),
            DriverStatus::getStatusId(DriverStatus::DRIVER_ON_ACCEPT) => $this->stateOnAccept(),
            DriverStatus::getStatusId(DriverStatus::DRIVER_ON_WAY) => $this->stateOnWay(),
            DriverStatus::getStatusId(DriverStatus::DRIVER_IN_PLACE) => $this->stateInPlace(),
            DriverStatus::getStatusId(DriverStatus::DRIVER_IN_ORDER) => $this->stateInOrder(),
            $this->driver->current_order->status_id === OrderStatus::getStatusId(OrderStatus::ORDER_PAUSED) => $this->statePauseOrder(),
            default => null,
        };
    }

    /**
     * @return array
     */
    protected function lockedWithLeft(): array
    {
        $this->driver->load('locked');
        $locked = ['locked' => false, 'locked_left' => 0];

        if (!$this->driver->locked) {
            return $locked;
        }

        $locked['locked'] = true;
        $locked['locked_left'] = now()->diffInMinutes($this->driver->locked->end);

        return $locked;
    }

    /**
     * @param  array  $locked
     * @return array
     */
    #[ArrayShape([
        'state' => 'mixed',
        'order_id' => 'int',
        'is_ready' => 'int',
        'locked' => 'bool',
        'locked_left' => 'int|mixed',
        'lat' => 'float|null',
        'lut' => 'float|null'
    ])] protected function listNumberFreeState(array $locked): array
    {
        if ($this->driver->last_completed_order->order_id ?? null) {
            $corporate = $this->orderCorporateContract
                ->where('order_id', '=', $this->driver->last_completed_order->order_id)
                ->where('slip_number', '=')
                ->findFirst(['order_corporate_id', 'order_id', 'driver_id', 'slip_number']);

            $state = $corporate ? DriverStatus::DRIVER_SLIP_NUMBER : DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE);
        } else {
            $state = DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE);
        }

        return [
            'state' => $state,
            'order_id' => $this->driver->last_completed_order->order_id ?? 0,
            'is_ready' => $this->driver->is_ready,
            'locked' => (bool)$locked['locked'],
            'locked_left' => $locked['locked'] ? $locked['locked_left'] : 0,
            'lat' => $this->driver->lat,
            'lut' => $this->driver->lut
        ];
    }

    /**
     * @return array
     */
    protected function stateOnAccept(): array
    {
        $locked = $this->lockedWithLeft();

        $this->driver->load([
            'current_order_shipment' => fn(HasOne $query) => $query->select([
                'order_shipped_driver_id',
                'driver_id',
                'order_id',
                'status_id',
                'on_way_hash'
            ]),
            'order_on_way_roads' => fn($query) => $query
                ->where('shipment_driver_id', '=', $this->driver->current_order_shipment->order_shipped_driver_id)
                ->select([
                    'order_on_way_roads.order_on_way_road_id',
                    'order_on_way_roads.shipment_driver_id',
                    'order_on_way_roads.route',
                    'order_on_way_roads.distance',
                    'order_on_way_roads.duration',
                    'order_on_way_roads.real_road'
                ]),
            'initial_order_data' => fn(HasOneDeep $query) => $query->select([
                'order_initial_data.order_initial_data_id',
                'order_initial_data.order_id',
                'order_initial_data.initial',
                'order_initial_data.distance',
                'order_initial_data.duration',
                'order_initial_data.price',
            ]),
        ]);

        return [
            'is_ready' => $this->driver->is_ready,
            'locked' => $locked['locked'] ? true : false,
            'locked_left' => $locked['locked'] ? $locked['locked_left'] : 0,
            'state' => DriverStatus::getStatusId(DriverStatus::DRIVER_ON_ACCEPT),
            'order_id' => $this->driver->current_order_shipment->order_id,
            'on_way_hash' => $this->driver->current_order_shipment->on_way_hash,
            'lat' => $this->driver->lat,
            'lut' => $this->driver->lut,

            'order' => [
                'initial' => $this->driver->initial_order_data->initial,
                'distance' => $this->driver->initial_order_data->distance,
                'duration' => $this->driver->initial_order_data->duration,
                'price' => $this->driver->initial_order_data->price,
                'client_phone' => $this->driver->order_client->phone,
                'address_from' => $this->driver->current_order->address_from,
                'from_coordinates' => $this->driver->current_order->from_coordinates
            ],

            'routes' => $this->driver->order_on_way_roads
        ];
    }

    /**
     * @return array
     */
    protected function stateOnWay(): array
    {
        $locked = $this->lockedWithLeft();

        $this->driver->load([
            'current_order_shipment' => fn(HasOne $query) => $query->select([
                'order_shipped_driver_id',
                'driver_id',
                'order_id',
                'status_id',
                'in_place_hash'
            ]),
            'order_on_way_road' => fn(HasOneThrough $query) => $query->select([
                'order_on_way_roads.order_on_way_road_id',
                'order_on_way_roads.shipment_driver_id',
                'order_on_way_roads.route',
                'order_on_way_roads.distance',
                'order_on_way_roads.duration',
                'order_on_way_roads.real_road',
            ]),
            'initial_order_data' => fn(HasOneDeep $query) => $query->select([
                'order_initial_data.order_initial_data_id',
                'order_initial_data.order_id',
                'order_initial_data.initial',
                'order_initial_data.distance',
                'order_initial_data.duration',
                'order_initial_data.price',
            ]),
            'current_order' => fn(HasOneDeep $query) => $query->select([
                'orders.order_id',
                'orders.address_from',
                'orders.from_coordinates',
            ]),
            'order_client' => fn(HasOneDeep $query) => $query->select([
                'clients.client_id',
                'clients.phone'
            ]),
        ]);

        return [
            'state' => DriverStatus::getStatusId(DriverStatus::DRIVER_ON_WAY),
            'order_id' => $this->driver->current_order_shipment->order_id,
            'in_place_hash' => $this->driver->current_order_shipment->in_place_hash,
            'is_ready' => $this->driver->is_ready,
            'locked' => (bool)$locked['locked'],
            'locked_left' => $locked['locked'] ? $locked['locked_left'] : 0,
            'lat' => $this->driver->lat,
            'lut' => $this->driver->lut,

            'routes' => [
                [
                    'road_id' => $this->driver->order_on_way_road->order_on_way_road_id,
                    'distance' => $this->driver->order_on_way_road->distance,
                    'duration' => $this->driver->order_on_way_road->duration,
                    'route' => $this->driver->order_on_way_road->route,
                    'real_road' => $this->driver->order_on_way_road->real_road
                ]
            ],

            'order' => [
                'initial' => $this->driver->initial_order_data->initial,
                'distance' => $this->driver->initial_order_data->distance,
                'duration' => $this->driver->initial_order_data->duration,
                'price' => $this->driver->initial_order_data->price,
                'client_phone' => $this->driver->order_client->phone,
                'address_from' => $this->driver->current_order->address_from,
                'from_coordinates' => $this->driver->current_order->from_coordinates
            ],
        ];
    }

    /**
     * @return array|null
     */
    protected function stateInPlace(): ?array
    {
        $locked = $this->lockedWithLeft();

        $this->driver->load([
                'current_order_shipment' => fn(HasOne $query) => $query->select([
                    'order_shipped_driver_id',
                    'driver_id',
                    'order_id',
                    'status_id',
                    'in_order_hash'
                ]),
                'order_in_process_road' => fn(HasOneThrough $query) => $query->select([
                    'order_in_process_roads.order_in_process_road_id',
                    'order_in_process_roads.shipment_driver_id',
                    'order_in_process_roads.route',
                    'order_in_process_roads.distance',
                    'order_in_process_roads.duration',
                    'order_in_process_roads.real_road',
                ]),

                'initial_order_data' => fn(HasOneDeep $query) => $query->select([
                    'order_initial_data.order_initial_data_id',
                    'order_initial_data.order_id',
                    'order_initial_data.initial',
                    'order_initial_data.distance',
                    'order_initial_data.duration',
                    'order_initial_data.price',
                ]),

                'current_order' => fn(HasOneDeep $query) => $query->select([
                    'orders.order_id',
                    'orders.address_from',
                    'orders.from_coordinates',
                    'orders.address_to',
                    'orders.to_coordinates'
                ]),

                'order_client' => fn(HasOneDeep $query) => $query->select(['clients.client_id', 'clients.phone']),
            ]
        );

        if (!$this->driver->order_in_process_road) {
            $this->driver->load([
                'order_in_process_roads' => fn(HasManyThrough $query) => $query
                    ->where('shipment_driver_id', '=', $this->driver->current_order_shipment->order_shipped_driver_id)
                    ->select([
                        'order_in_process_roads.order_in_process_road_id',
                        'order_in_process_roads.shipment_driver_id',
                        'order_in_process_roads.route',
                        'order_in_process_roads.distance',
                        'order_in_process_roads.duration',
                        'order_in_process_roads.real_road',
                    ])
            ]);
        }

        return [
            'state' => DriverStatus::getStatusId(DriverStatus::DRIVER_IN_PLACE),
            'order_id' => $this->driver->current_order_shipment->order_id,
            'in_order_hash' => $this->driver->current_order_shipment->in_order_hash,
            'is_ready' => $this->driver->is_ready,
            'locked' => $locked['locked'] ? true : false,
            'locked_left' => $locked['locked'] ? $locked['locked_left'] : 0,
            'lat' => $this->driver->lat,
            'lut' => $this->driver->lut,

            'routes' => $this->driver->order_in_process_road ? [
                [
                    'road_id' => $this->driver->order_in_process_road->order_in_process_road_id,
                    'distance' => $this->driver->order_in_process_road->distance,
                    'duration' => $this->driver->order_in_process_road->duration,
                    'route' => $this->driver->order_in_process_road->route,
                ]
            ] : $this->driver->order_in_process_roads,

            'order' => [
                'initial' => $this->driver->initial_order_data->initial,
                'distance' => $this->driver->initial_order_data->distance,
                'duration' => $this->driver->initial_order_data->duration,
                'price' => $this->driver->initial_order_data->price,
                'client_phone' => $this->driver->order_client->phone,
                'address_from' => $this->driver->current_order->address_from,
                'from_coordinates' => $this->driver->current_order->from_coordinates,
                'address_to' => $this->driver->current_order->address_to,
                'to_coordinates' => $this->driver->current_order->to_coordinates,
            ],
        ];
    }

    /**
     * @return array|null
     */
    public function stateInOrder(): ?array
    {
        $locked = $this->lockedWithLeft();

        $this->driver->load([
            'current_order_shipment' => fn(HasOne $query) => $query->select([
                'order_shipped_driver_id',
                'driver_id',
                'order_id',
                'status_id',
                'end_hash',
                'pause_hash'
            ]),
            'order_in_process_road' => fn(HasOneThrough $query) => $query->select([
                'order_in_process_roads.order_in_process_road_id',
                'order_in_process_roads.shipment_driver_id',
                'order_in_process_roads.route',
                'order_in_process_roads.distance',
                'order_in_process_roads.duration',
                'order_in_process_roads.real_road',
            ]),
            'initial_order_data' => fn(HasOneDeep $query) => $query->select([
                'order_initial_data.order_initial_data_id',
                'order_initial_data.order_id',
                'order_initial_data.initial',
                'order_initial_data.distance',
                'order_initial_data.duration',
                'order_initial_data.price',
            ]),
            'current_order' => fn(HasOneDeep $query) => $query->select([
                'orders.order_id',
                'orders.address_from',
                'orders.from_coordinates',
                'orders.address_to',
                'orders.to_coordinates',
                'orders.status_id',
            ]),
            'order_client' => fn(HasOneDeep $query) => $query->select([
                'clients.client_id',
                'clients.phone'
            ]),
            'process' => fn(HasOneThrough $query) => $query->select([
                'order_process_id',
                'order_shipped_id',
                'total_price',
                'travel_time',
                'distance_traveled',
                'pause_time'
            ])
        ]);

        return [
            'state' => DriverStatus::getStatusId(DriverStatus::DRIVER_IN_ORDER),
            'order_id' => $this->driver->current_order_shipment->order_id,
            'end_hash' => $this->driver->current_order_shipment->end_hash,
            'pause_hash' => $this->driver->current_order_shipment->pause_hash,
            'is_ready' => $this->driver->is_ready,
            'locked' => $locked['locked'] ? true : false,
            'locked_left' => $locked['locked'] ? $locked['locked_left'] : 0,
            'lat' => $this->driver->lat,
            'lut' => $this->driver->lut,

            'routes' => [
                [
                    'road_id' => $this->driver->order_in_process_road->order_in_process_road_id,
                    'distance' => $this->driver->order_in_process_road->distance,
                    'duration' => $this->driver->order_in_process_road->duration,
                    'route' => $this->driver->order_in_process_road->route,
                    'real_road' => $this->driver->order_in_process_road->real_road
                ]
            ],

            'order' => [
                'distance' => $this->driver->process->distance_traveled,
                'duration' => $this->driver->process->travel_time,
                'pause_time' => $this->driver->process->pause_time,
                'paused' => $this->driver->current_order->status_id === OrderStatus::getStatusId(OrderStatus::ORDER_PAUSED),
                'price' => $this->driver->process->total_price,
                'client_phone' => $this->driver->order_client->phone,
                'address_from' => $this->driver->current_order->address_from,
                'address_to' => $this->driver->current_order->address_to,
                'from_coordinates' => $this->driver->current_order->from_coordinates,
                'to_coordinates' => $this->driver->current_order->to_coordinates,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function statePauseOrder(): array
    {
        return $this->stateInOrder();
    }
}
