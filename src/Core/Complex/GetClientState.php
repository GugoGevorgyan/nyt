<?php

declare(strict_types=1);


namespace Src\Core\Complex;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Enums\ConstClientStatus;
use Src\Core\Traits\Complex;
use Src\Models\Client\Client;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\Order;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\OrderFeedbackOption\OrderFeedbackOptionContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Services\Client\ClientServiceContract;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * Class GetClientState
 *
 * @method static complex(Client $client)
 * @property ClientServiceContract $clientService
 * @property OrderFeedbackOptionContract $feedbackOptionContract
 * @property OrderShippedDriverContract $orderShippedDriverContract
 * @property DriverContract $driverContract
 */
final class GetClientState
{
    use Complex;

    /**
     * @var Client
     */
    protected Client $client;
    /**
     * @var Order|null
     */
    protected ?Order $order;
    /**
     * @var Driver|null
     */
    protected ?Driver $driver;

    /**
     * GetClientState constructor.
     * @param  Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param  ClientServiceContract  $clientService
     * @param  OrderFeedbackOptionContract  $feedbackOptionContract
     * @param  OrderShippedDriverContract  $orderShippedDriverContract
     * @param  DriverContract  $driverContract
     * @return array|null
     */
    public function handle(
        ClientServiceContract $clientService,
        OrderFeedbackOptionContract $feedbackOptionContract,
        OrderShippedDriverContract $orderShippedDriverContract,
        DriverContract $driverContract
    ): ?array {
        if (!$this->client->in_order && !$this->clientService->hasOrderWithoutAssessment($this->client->client_id)) {
            return $this->clientService->getRealState($this->client);
        }

        $this->client->load([
            'current_order_driver' => fn(HasOneDeep $query) => $query->select([
                'drivers.driver_id',
                'drivers.current_status_id',
                'drivers.lat',
                'drivers.lut',
                'drivers.azimuth',
                'drivers.car_id',
                'drivers.driver_info_id'
            ]),
            'current_order' => fn(HasOne $query) => $query->select([
                'orders.order_id',
                'orders.status_id',
                'orders.client_id',
                'address_from',
                'address_to'
            ]),
        ]);
        $this->driver = $this->client->current_order_driver ?? null;
        $this->order = $this->client->current_order;

        if (!$this->driver && !$this->order && !$this->clientService->hasOrderWithoutAssessment($this->client->client_id)) {
            return ['status' => ConstClientStatus::stateless()->getValue(), 'payload' => []];
        }

        return $this->statusDistributor();
    }

    /**
     * @return array|null
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function statusDistributor(): ?array
    {
        if ($assessment = $this->clientService->hasOrderWithoutAssessment($this->client->client_id)) {
            return $this->assessmentStage($assessment);
        }


        if ($this->order) {
            if ($this->order->status_id === OrderStatus::ORDER_PENDING) {
                return $this->orderPending();
            }

            if ($this->order->status_id === OrderStatus::ORDER_IN_PROCESS && $this->driver->current_status_id === DriverStatus::DRIVER_ON_ACCEPT) {
                return $this->driverAcceptOrder();
            }

            if ($this->order->status_id === OrderStatus::ORDER_IN_PROCESS && $this->driver->current_status_id === DriverStatus::DRIVER_ON_WAY) {
                return $this->driverOnWay();
            }

            if ($this->order->status_id === OrderStatus::ORDER_IN_PROCESS && $this->driver->current_status_id === DriverStatus::DRIVER_IN_PLACE) {
                return $this->driverInPlace();
            }

            if (($this->order->status_id === OrderStatus::ORDER_IN_PROCESS || $this->order->status_id === OrderStatus::ORDER_PAUSED)
                && $this->driver->current_status_id === DriverStatus::DRIVER_IN_ORDER) {
                return $this->driverInOrder();
            }
        }

        return null;
    }

    /**
     * @param $order_id
     * @return array
     */
    #[ArrayShape([
        'message' => 'mixed',
        'status' => 'mixed',
        'order' => 'array',
        'feedbacks' => '\Illuminate\Support\Collection'
    ])]
    public function assessmentStage($order_id): array
    {
        $feedbacks = $this->feedbackOptionContract
            ->where('owner_type', '=', $this->client->getMap())
            ->where('completed', '=', true)
            ->where('canceled', '=', false)
            ->whereRaw('FIND_IN_SET(3, assessment)')
            ->findAll(['option', 'name']);

        $this->client->load([
            'completed_order' => fn(HasOneThrough $query) => $query
                ->where('completed_orders.order_id', '=', $order_id)
                ->select('completed_orders.order_id', 'completed_orders.completed_order_id', 'completed_orders.cost')
        ]);

        return [
            'message' => trans('messages.order_completed'),
            'status' => ConstClientStatus::assessment()->getValue(),

            'order' => [
                'order_id' => $this->client->completed_order->completed_order_id,
                'price' => $this->client->completed_order->cost,
            ],

            'feedbacks' => $feedbacks,
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'status' => 'mixed',
        'message' => 'mixed'
    ])]
    protected function orderPending(): array
    {
        $this->getInitialData();

        return [
            'status' => ConstClientStatus::pending_search()->getValue(),
            'message' => trans('messages.order_search_car'),
        ];
    }

    /**
     * Load Initial Order Data For Client
     */
    private function getInitialData(): void
    {
        $this->client->load([
            'initial_order_data' => fn(MorphOne $query) => $query
                ->where('order_id', '=', $this->order->order_id)
                ->select([
                    'order_initial_data.order_initial_data_id',
                    'order_initial_data.order_id',
                    'order_initial_data.orderable_id',
                    'order_initial_data.orderable_type',
                    'order_initial_data.lat',
                    'order_initial_data.lut',
                    'order_initial_data.price',
                    'order_initial_data.currency',
                    'order_initial_data.sitting_fee'
                ])
        ]);
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'status' => 'mixed',
        'message' => 'mixed',
        'driver' => 'array'
    ])]
    protected function driverAcceptOrder(): array
    {
        $this->getInitialData();
        $this->getCarDriverInfo();

        return [
            'status' => ConstClientStatus::accept_order()->getValue(),
            'message' => trans('messages.driver_accepted_order'),

            'driver' => [
                'name' => $this->driver->driver_info->name,
                'surname' => $this->driver->driver_info->surname,
                'photo' => $this->driver->driver_info->photo,
                'car' => [
                    'mark' => $this->driver->car->mark,
                    'model' => $this->driver->car->model,
                    'color' => $this->driver->car->color,
                    'sts_number' => $this->driver->car->sts_number,
                    'state_license_place' => $this->driver->car->state_license_plate,
                ]
            ],


        ];
    }

    /**
     * @param  bool  $road
     * @param  bool  $info
     */
    private function getCarDriverInfo(bool $road = false, bool $info = false): void
    {
        $this->driver
            ->load([
                'car' => fn(BelongsTo $query) => $query->select([
                    'car_id',
                    'current_driver_id',
                    'mark',
                    'model',
                    'state_license_plate',
                    'color',
                    'sts_number'
                ])
            ]);

        if ($road) {
            $this->driver->load([
                'order_on_way_road' => fn(HasOneThrough $query) => $query
                    ->whereHas('shipment_driver', fn(Builder $q) => $q
                        ->where('order_id', '=', $this->order->order_id)
                        ->select(['order_on_way_road_id', 'shipment_driver_id', 'duration'])
                    ),
                'current_order_shipment' => fn($query) => $query->select(['order_shipped_driver_id', 'driver_id', 'accept_hash', 'in_place_hash'])
            ]);
        }

        if ($info) {
            $this->driver->load(['driver_info' => fn($query) => $query->select(['name', 'surname', 'photo', 'driver_info_id'])]);
        }
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'status' => 'mixed',
        'minute' => 'mixed',
        'message' => 'mixed',
        'driver' => 'array'
    ])]
    protected function driverOnWay(): array
    {
        $this->getInitialData();
        $this->getCarDriverInfo(true);

        return [
            'status' => ConstClientStatus::expect_taxi()->getValue(),
            'minute' => trans('messages.driver_arrival_time', ['minute' => $this->driverArrivalTime()]),
            'message' => trans('messages.driver_go_to_you'),

            'driver' => [
                'name' => $this->driver->driver_info->name,
                'surname' => $this->driver->driver_info->surname,
                'photo' => $this->driver->driver_info->photo,
                'car' => [
                    'mark' => $this->driver->car->mark,
                    'model' => $this->driver->car->model,
                    'color' => $this->driver->car->color,
                    'sts_number' => $this->driver->car->sts_number,
                    'state_license_place' => $this->driver->car->state_license_plate,
                    'on_way_duration' => $this->driver->order_on_way_road->duration
                ]
            ],
        ];
    }

    /**
     * @return int|null
     */
    protected function driverArrivalTime(): ?int
    {
        $this->client->load([
            'current_order_process' => fn(HasOneDeep $query) => $query->select(['order_process_id', 'order_shipped_id', 'travel_time']),
            'current_order_on_way_road' => fn(HasOneDeep $query) => $query->select(['order_on_way_road_id', 'shipment_driver_id', 'duration']),
        ]);

        $minute = ($this->client->current_order_on_way_road->duration * 60) - $this->client->current_order_process->travel_time ?? null;
        return $minute ? (int)round($minute / 60) : null;
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'status' => 'mixed',
        'message' => 'mixed',
        'driver' => 'array',
        'tariff_features' => 'mixed'
    ])]
    protected function driverInPlace(): array
    {
        $this->getInitialData();
        $this->getCarDriverInfo(true, true);

        $currentOrder = $this->orderShippedDriverContract
            ->where('in_place_hash', '=', $this->driver->current_order_shipment->in_place_hash)
            ->with([
                'initial_order_tariff' => fn($query) => $query->select(['tariff_id', 'free_wait_minutes', 'paid_wait_minute'])
            ])
            ->findFirst(['order_shipped_driver_id', 'order_id', 'driver_id']);

        return [
            'status' => ConstClientStatus::waiting_taxi()->getValue(),
            'message' => trans('messages.driver_in_place'),

            'driver' => [
                'name' => $this->driver->driver_info->name,
                'surname' => $this->driver->driver_info->surname,
                'photo' => $this->driver->driver_info->photo,
                'car' => [
                    'mark' => $this->driver->car->mark,
                    'model' => $this->driver->car->model,
                    'color' => $this->driver->car->color,
                    'sts_number' => $this->driver->car->sts_number,
                    'state_license_place' => $this->driver->car->state_license_plate,
                    'on_way_duration' => $this->driver->order_on_way_road->duration
                ]
            ],
            'tariff_features' => $currentOrder['initial_order_tariff']
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'status' => 'mixed',
        'message' => 'mixed',
        'driver' => 'array'
    ])]
    public function driverInOrder(): array
    {
        $this->getInitialData();
        $this->getCarDriverInfo(true, true);

        return [
            'status' => ConstClientStatus::order()->getValue(),
            'message' => trans('messages.order_in_process'),
            'driver' => [
                'name' => $this->driver->driver_info->name,
                'surname' => $this->driver->driver_info->surname,
                'photo' => $this->driver->driver_info->photo,
                'car' => [
                    'mark' => $this->driver->car->mark,
                    'model' => $this->driver->car->model,
                    'color' => $this->driver->car->color,
                    'sts_number' => $this->driver->car->sts_number,
                    'state_license_place' => $this->driver->car->state_license_plate,
                    'on_way_duration' => $this->driver->order_on_way_road->duration
                ]
            ],
        ];
    }
}
