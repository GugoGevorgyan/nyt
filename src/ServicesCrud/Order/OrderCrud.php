<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use JsonException;
use Src\Core\Additional\Devicer;
use Src\Core\Enums\ConstQueue;
use Src\Exceptions\Lexcept;
use Src\Jobs\OrderProcessing\OrderShippedTikTak;
use Src\Models\Order\CompletedOrder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\CompletedOrder\CompletedOrderContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderAttach\OrderAttachContract;
use Src\Repositories\OrderCorporate\OrderCorporateContract;
use Src\Repositories\OrderMeet\OrderMeetContract;
use Src\Repositories\OrderRent\OrderRentContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Auth\AuthServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\RatingPointService\RatingPointService;
use Src\Services\Tariff\TariffServiceContract;
use Src\ServicesCrud\BaseCrud;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * Class OrderCrud
 * @package Src\Services\Order
 */
class OrderCrud extends BaseCrud implements OrderCrudContract
{
    use OrderCrudTrait;

    /**
     * OrderCreateService constructor.
     * @param  OrderContract  $orderContract
     * @param  ClientContract  $clientContract
     * @param  OrderMeetContract  $orderMeetContract
     * @param  RatingPointService  $ratingPointService
     * @param  OrderShippedDriverContract  $orderingShipmentContract
     * @param  GeocodeServiceContract  $geoService
     * @param  CompanyContract  $companyContract
     * @param  InitialOrderDataContract  $initialOrderContract
     * @param  TariffServiceContract  $tariffService
     * @param  AuthServiceContract  $authService
     * @param  CompletedOrderContract  $completedOrderContract
     * @param  DriverContract  $driverContract
     * @param  OrderServiceContract  $orderService
     * @param  OrderAttachContract  $orderAttachContract
     * @param  OrderCorporateContract  $orderCorporateContract
     * @param  PreorderContract  $preorderContract
     * @param  OrderRentContract  $orderRentContract
     * @param  FranchiseContract  $franchiseContract
     */
    public function __construct(
        protected OrderContract $orderContract,
        protected ClientContract $clientContract,
        protected OrderMeetContract $orderMeetContract,
        protected RatingPointService $ratingPointService,
        protected OrderShippedDriverContract $orderingShipmentContract,
        protected GeocodeServiceContract $geoService,
        protected CompanyContract $companyContract,
        protected InitialOrderDataContract $initialOrderContract,
        protected TariffServiceContract $tariffService,
        protected AuthServiceContract $authService,
        protected CompletedOrderContract $completedOrderContract,
        protected DriverContract $driverContract,
        protected OrderServiceContract $orderService,
        protected OrderAttachContract $orderAttachContract,
        protected OrderCorporateContract $orderCorporateContract,
        protected PreorderContract $preorderContract,
        protected OrderRentContract $orderRentContract,
        protected FranchiseContract $franchiseContract
    ) {
    }

    /**
     * @param $order
     * @return int
     */
    public function getOrderTypeId($order): int
    {
        if (!empty($order['passenger']) && $order['passenger']) {
            return Order::ORDER_TYPE_TO_OTHER;
        }

        if ($order['payment_type_id'] === Order::PAYMENT_TYPE_CASH) {
            return Order::ORDER_TYPE_CLIENT;
        }

        if ($order['payment_type_id'] === Order::PAYMENT_TYPE_PAY_COMPANY) {
            return Order::ORDER_TYPE_CLIENT_BY_COMPANY;
        }

        return Order::ORDER_TYPE_CLIENT;
    }

    /**
     * @param $options
     * @return array
     */
    public function getOptions($options): array
    {
        return ['ids' => $options];
    }

    /**
     * @param $formData
     * @return Carbon|null
     */
    public function getOrderStart($formData): ?Carbon
    {
        if ($formData['order_start']) {
            return $formData['order_start'];
        }

        if ($formData['pre_order_minutes']) {
            return Carbon::now()->addMinutes($formData['pre_order_minutes']);
        }

        return null;
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @param  int  $tik_time
     * @throws Lexcept
     */
    public function orderAttachToDriver($order_id, $driver_id, int $tik_time = 11): bool
    {
        $order = $this->orderContract->find($order_id, ['order_id', 'address_from', 'from_coordinates', 'address_to']);
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'phone', 'car_id', 'current_franchise_id', 'lat', 'lut']);

        if (!$order || !$driver) {
            throw new Lexcept('Invalid order or driver', 500);
        }

        $distance = $this->geoService->roadCalculation($order->from_coordinates, ['lat' => $driver->lat, 'lut' => $driver->lut]);

        $shipped_values = $this->orderService->createOrderForDriver(
            $driver_id,
            $order->order_id,
            $order->address_from,
            $distance['distance'],
            $distance['duration'],
            $distance['points']
        );

        $this->orderAttachContract->updateOrCreate([
            'order_id', '=', $order->order_id,
            'driver_id', '=', $driver->driver_id
        ], [
            'order_id' => $order->order_id,
            'driver_id' => $driver->driver_id,
            'shipped_id' => $shipped_values['shipped_id'],
            'system_worker_id' => user()->system_worker_id,
        ]);

        OrderShippedTikTak::dispatch($driver, $shipped_values, $tik_time)->onQueue(ConstQueue::LONG()->getValue());

        return true;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function orderDataFilter($data): Collection
    {
        $passenger = $this->clientContract->where('phone', '=', $data['phone']['passenger'])->findFirst(['client_id', 'phone']);

        $filter_data = [
            // Order Table Data
            'order' => $this->orderSchemaFilter($data),
            // End Order Table Data
            // Pre Order Table data
            'preorder' => $this->detectOrderSchedule($data['time'])->get('diff') > $this->franchiseDispatchingMinute() ?
                [
                    'create_time' => $data['time']['create_time'],
                    'time_zone' => $data['time']['zone'],
                    'time' => $data['time']['time'],
                ] :
                [],
            // Meet Table Data
            'meet' => isset($data['meet']['is_meet']) && $data['meet']['is_meet']
                ? [
                    'place_id' => $data['meet']['place_id'],
                    'place_type' => $data['meet']['place_type'],
                    'info' => $data['meet']['number'],
                    'text' => $data['meet']['text'],
                ]
                : [],
            // Passenger Data table
            'passenger' => $data['phone']['passenger'] ? ($passenger ? ['passenger_id' => $passenger->client_id] : ['phone' => $data['phone']['passenger']]) : [],
            // Corporate Order data table
            'corporate' => $data['payment']['type'] === Order::PAYMENT_TYPE_PAY_COMPANY ? ['company_id' => $data['payment']['company']] : [],
            // Rent data table
            'rent' => isset($data['is_rent']) && $data['is_rent'] ? ['hours' => $data['rent_time']] : [],
        ];

        return $this->parseResult($filter_data);
    }

    /**
     * @param $data
     * @return array[]
     * @throws JsonException
     */
    protected function orderSchemaFilter($data): array
    {
        return [
            'phone' => preg_replace('/\D/', '', $data['phone']['client']),
            'client_id' => $data['client_id'] ?? user()->{user()->getKeyName()},
            'address_from' => $data['route']['from_address'],
            'address_to' => $data['route']['to_address'],
            'from_coordinates' => $data['route']['from'] ? decode(['lat' => $data['route']['from'][0], 'lut' => $data['route']['from'][1]]) : decode(),
            'to_coordinates' => !empty($data['route']['to']) ? decode(['lat' => $data['route']['to'][0], 'lut' => $data['route']['to'][1]]) : decode(),
            'car_class_id' => $data['car']['class'],
            'car_option' => decode(['ids' => $data['car']['options'] ?: []]),
            'comment' => $data['car']['comments'],
            'platform' => (new Devicer())->device(),
            'franchisee' => decode(['ids' => session('order.from_franchise_ids') ?: []]),
            'payment_type_id' => $this->detectPaymentType($data['payment']),
            'status_id' => OrderStatus::ORDER_PENDING,
            'order_type_id' => $this->detectOrderType($data['phone'], $data['payment']),
            'customer_id' => get_user_id(),
            'customer_type' => user()->getMap(),
            'location_zone_id' => $data['location_zone_id'],
            'customer_zone_id' => $data['customer_zone_id'],
        ];
    }

    /**
     * @param  array  $payments
     * @return int|null
     */
    protected function detectPaymentType(array $payments): ?int
    {
        $payment_type = null;

        switch ($payments['type']) {
            case Order::PAYMENT_TYPE_CASH:
                $payment_type = Order::PAYMENT_TYPE_CASH;
                break;
            case Order::PAYMENT_TYPE_PAY_COMPANY:
                $payment_type = Order::PAYMENT_TYPE_PAY_COMPANY;
                break;
            case Order::PAYMENT_TYPE_CREDIT:
                $payment_type = Order::PAYMENT_TYPE_CREDIT;
                break;
            default:
        }

        return $payment_type;
    }

    /**
     * @param  array  $phones
     * @param  array  $payments
     * @return int|null
     */
    protected function detectOrderType(array $phones, array $payments): ?int
    {
        $order_type = null;

        if ($phones['client'] && !$phones['passenger']) {
            $order_type = Order::ORDER_TYPE_CLIENT;
        }

        if ($phones['client'] && $phones['passenger']) {
            $order_type = Order::ORDER_TYPE_TO_OTHER;
        }

        if ($phones['client'] && $payments['company']) {
            $order_type = Order::ORDER_TYPE_CLIENT_BY_COMPANY;
        }

        if ($phones['client'] && $payments['company'] && 'client' !== user()->getMap()) {
            $order_type = Order::ORDER_TYPE_COMPANY_TO_CLIENT;
        }

        return $order_type;
    }

    /**
     * @param  array  $data
     * @return Collection
     */
    protected function detectOrderSchedule(array $data): Collection
    {
        $order_create_time = Carbon::parse($data['create_time']);
        $order_time = Carbon::parse($data['time']);
        $order_zone = Carbon::parse($data['zone']);
        $diff = $order_create_time->diffInMinutes($order_time);

        return $this->parseResult(
            $instance,
            ['diff', 'create_time', 'order_time', 'time_zone'],
            [$diff, $order_create_time, $order_time, $order_zone]
        );
    }

    /**
     * @return int|float
     */
    protected function franchiseDispatchingMinute(): float|int
    {
        $franchise_ids = session('order.from_franchise_ids');

        if ($franchise_ids) {
            return $this->franchiseContract
                    ->with(['option' => fn($query) => $query->select(['franchise_id', 'dispatching_minute'])])
                    ->whereIn('franchise_id', $franchise_ids)
                    ->findAll(['franchise_id'])
                    ->avg('dispatching_minute')
                ?? 30;
        }

        return 30;
    }

    /**
     * @param $timeData
     * @return array
     */
    public function compareTimeWithPreOrderTime(array $timeData): array
    {
        if (Carbon::createFromDate($timeData['create_time'])->format('Y-m-d H:i') < $timeData['time']) {
            $client_preorders = $this->clientContract
                ->where('client_id', '=', get_user_id())
                ->with([
                    'pre_orders' => fn($query) => $query
                        ->where('active', '=', true)
                        ->whereDate('time', '>', $timeData['create_time'])
                        ->select(['preorder_id', 'preorders.order_id', 'time'])
                ])
                ->findFirst(['client_id']);

            if ($client_preorders && !empty($client_preorders->pre_orders)) {
                $warnings = [];

                foreach ($client_preorders->pre_orders as $order) {
                    if ($order['time'] > $timeData['time']) {
                        if (!Carbon::parse($order['time'])->subMinutes(30)->greaterThan(Carbon::parse($timeData['time']))) {
                            $warnings[] = $order;
                        }
                    } elseif (!Carbon::parse($order['time'])->addMinutes(30)->lessThan(Carbon::parse($timeData['time']))) {
                        $warnings[] = $order;
                    }
                }
                if (!empty($warnings)) {
                    return ['message' => trans('messages.pre_order_check_time_compare_preorder_times'), 'status' => false];
                }
            }

            return ['status' => true];
        }

        $preOrders = $this->orderContract
            ->where('client_id', '=', get_user_id())
            ->whereHas('preorder', fn($query) => $query
                ->where('active', '=', true)
                ->where('time', '<=', Carbon::parse($timeData['time'])->addMinutes(20))
            )
            ->findFirst();

        if (!$preOrders) {
            return ['status' => true];
        }

        return ['message' => trans('messages.order_check_time_compare_preorder_times'), 'status' => false];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function companyClientOrderFilter(array $data): Collection
    {
        $order = $data['order'];
        $meet = $data['meet'];

        $passenger = $this->clientContract->where('phone', '=', $order['phone']['passenger'])->findFirst(['client_id', 'phone']);

        $filter_data = [
            // Order Table Data
            'order' => $this->orderSchemaFilter($order),
            'preorder' => $this->detectOrderSchedule($order['time'])->get('diff') > $this->franchiseDispatchingMinute() ?
                [
                    'create_time' => $data['order']['time']['create_time'],
                    'time_zone' => $data['order']['time']['zone'],
                    'time' => $data['order']['time']['time'],
                ] :
                [],
            // Meet Table Data
            'meet' => isset($meet['is_meet']) && $meet['is_meet']
                ? [
                    'place_id' => $meet['place_id'],
                    'place_type' => $meet['place_type'],
                    'info' => $meet['number'],
                    'text' => $meet['text'],
                ]
                : [],
            // Passenger Data table
            'passenger' => $order['phone']['passenger'] ? ($passenger ? ['passenger_id' => $passenger->client_id] : ['phone' => $data['phone']['passenger']]) : [],
            // Corporate Order data table
            'corporate' => $order['payment']['type'] === Order::PAYMENT_TYPE_PAY_COMPANY ? ['company_id' => $order['payment']['company']] : [],
            'rent' => isset($data['order']['is_rent']) && $data['order']['is_rent'] ? ['hours' => $data['order']['rent_time']] : [],
        ];

        return $this->parseResult($filter_data);
    }

    /**
     * @inheritDoc
     */
    public function getDriverOrdersList(int $driver_id, $take = null, $skip = null): Collection
    {
        return $this->completedOrderContract
            ->with([
                'order' => fn(BelongsTo $query) => $query->select(['order_id', 'address_from', 'address_to']),
                'stage' => fn(HasOneDeep $query) => $query->select(['orders.order_id', 'started', 'ended'])
            ])
            ->limit($take)
            ->offset($skip)
            ->orderBy('completed_order_id', 'desc')
            ->findWhere(
                ['driver_id', '=', $driver_id],
                ['completed_order_id', 'order_id', 'driver_id', 'distance', 'duration', 'cost', 'destination_address', 'created_at']
            );
    }

    /**
     * @inheritDoc
     */
    public function getDriverOrderTrajectory(int $completed_order_id): CompletedOrder
    {
        return $this->completedOrderContract
            ->with([
                'process' => fn($query) => $query->select([
                    'order_process_id',
                    'order_shipped_id',
                    'pause_time',
                    'options_price',
                    'pause_price',
                    'waiting_price'
                ]),
                'order' => fn($query) => $query->select(['order_id', 'address_from', 'address_to', 'payment_type_id']),
                'stage' => fn($query) => $query->select(['order_stage_cord_id', 'started', 'ended', 'pauses']),
                'payment_type' => fn($query) => $query->select(['payment_types.payment_type_id', 'name', 'text']),
                'tariff' => fn($query) => $query
                    ->with(['current_tariff' => fn($query) => $query->select(['*'])])
                    ->select(['tariff_id', 'tariffable_id', 'tariffable_type', 'name', 'minimal_price']),
                'initial' => fn($query) => $query->select(['order_initial_data_id', 'order_id', 'initial_tariff_id', 'price']),
                'crossing' => fn($query) => $query->select([
                    'in_price',
                    'out_price',
                    'in_trajectory',
                    'out_trajectory',
                    'in_distance',
                    'out_distance',
                    'in_duration',
                    'out_duration'
                ])
            ])
            ->find($completed_order_id, [
                'completed_order_id',
                'order_id',
                'trajectory',
                'distance',
                'duration',
                'duration_price',
                'duration_price',
                'cost',
                'destination_address',
            ]);
    }

    /**
     * @param  array  $form_data
     * @return Collection|null
     * @throws Exception
     */
    public function createCallCenterOrder(array $form_data): ?Collection
    {
        $client = $this->clientContract->find($form_data['order']['client_id']);

        if ($client && $client->in_order) {
            throw new Lexcept('Client already in order', 500);
        }

        $data['order'] = $this->callCenterOrderFilterData($form_data['order']);

        if (!$data['order']) {
            return null;
        }

        $data['is_company'] = (bool)$form_data['order']['company_id'];
        $data['corporate'] = ['company_id' => $form_data['order']['company_id']];

        $data['is_passenger'] = $form_data['order']['is_passenger'];
        $data['passenger'] = $form_data['order']['passenger_id'] ?? '';
        $data['order']['passenger_id'] = $data['is_passenger'] ? $this->callCenterPassengerFilterData($form_data['orderPassenger']) : null;

        $data['is_meet'] = $form_data['order']['is_meet'];
        $data['meet'] = $data['is_meet'] ? $this->callCenterMeetFilterData($form_data['orderMeet']) : null;

        $data['is_preorder'] = $form_data['order']['is_preorder'];

        $time_data = [
            'create_time' => $form_data['order']['create_time'],
            'zone' => $form_data['order']['time_zone'],
            'time' => $form_data['order']['start_time'],
        ];

        $data['preorder'] = $this->detectOrderSchedule($time_data)->get('diff') > $this->franchiseDispatchingMinute() ? $this->callCenterPreOrderFilterData($form_data['order']) : [];

        return $this->createOrder(collect($data), false);
    }

    /**
     * @param  Collection  $order_data
     * @param  bool  $create_adds
     * @return mixed|void
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    public function createOrder(Collection $order_data, bool $create_adds = true): ?Collection
    {
        $this->orderContract->forgetCache();

        $this->orderContract->beginTransaction();
        $order = $this->orderContract->create($order_data->get('order'));

        if (!$order) {
            $this->orderContract->rollBack();
            return null;
        }

        $corporate = $order_data->get('corporate');
        data_set($corporate, 'order_id', $order->order_id, false);
        $this->orderCorporateContract->forgetCache();
        if ($order_data->get('corporate') && $order_data->get('corporate')['company_id'] && !$this->orderCorporateContract->create($corporate)) {
            $this->orderContract->rollBack();
            return null;
        }

        $meet = $order_data->get('meet');
        data_set($meet, 'order_id', $order->order_id, false);
        $this->orderMeetContract->forgetCache();
        if ($order_data->get('meet') && !$this->orderMeetContract->create($meet)) {
            $this->orderContract->rollBack();
            return null;
        }

        $preorder = $order_data->get('preorder');
        data_set($preorder, 'order_id', $order->order_id, false);
        $this->preorderContract->forgetCache();
        if ($order_data->get('preorder') && !$this->preorderContract->create($preorder)) {
            $this->orderContract->rollBack();
            return null;
        }

        $rent = $order_data->get('rent');
        data_set($rent, 'order_id', $order->order_id, false);
        $this->orderRentContract->forgetCache();
        if ($order_data->get('rent') && !$this->orderRentContract->create($rent)) {
            $this->orderContract->rollBack();
            return null;
        }

        $clause = $create_adds && $order_data->get('passenger') && !$this->clientContract->where('phone', '=', $order_data->get('passenger'))->exists('phone');
        $clause ? $this->clientContract->forgetCache() : null;

        if ($clause && !$this->clientContract->create(['phone' => $order_data->get('passenger'), 'passenger' => true])) {
            $this->orderContract->rollBack();
            return null;
        }

        $this->orderContract->commit();

        return $this->orderService->afterOrderCreate($order_data->all(), $order);
    }
}
