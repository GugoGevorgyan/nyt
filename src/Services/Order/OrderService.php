<?php

declare(strict_types=1);


namespace Src\Services\Order;

use DB;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use ServiceEntity\BaseService;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstRedis;
use Src\Exceptions\Lexcept;
use Src\Exceptions\Order\OrderCanceledInSearchDriverException;
use Src\Jobs\OrderProcessing\OrderDistributor;
use Src\Jobs\OrderProcessing\PauseOrder;
use Src\Jobs\SendNotifies;
use Src\Models\Driver\Driver;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Models\SystemUsers\SystemWorker;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\CarOption\CarOptionContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\CompletedOrder\CompletedOrderContract;
use Src\Repositories\CorporateClient\CorporateClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderCorporate\OrderCorporateContract;
use Src\Repositories\OrderInProcessRoad\OrderInProcessRoadContract;
use Src\Repositories\OrderOnWayRoad\OrderOnWayRoadContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\OrderStageCord\OrderStageCordContract;
use Src\Repositories\OrderStatus\OrderStatusContract;
use Src\Repositories\OrderType\OrderTypeContract;
use Src\Repositories\OrderWorkerComment\OrderWorkerCommentContract;
use Src\Repositories\PaymentType\PaymentTypeContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Repositories\Waybill\WaybillContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Client\ClientTrait;
use Src\Services\RatingPointService\RatingPointServiceContract;
use Src\Services\Tariff\TariffServiceContract;

use function count;

/**
 * Class OrderService
 * @package Src\Services\Order
 */
final class OrderService extends BaseService implements OrderServiceContract
{
    use ClientTrait;
    use OrderTrait;

    /**
     * @param  OrderContract  $orderContract
     * @param  CorporateClientContract  $corporateClientContract
     * @param  PaymentTypeContract  $paymentTypeContract
     * @param  CarClassContract  $carClassContract
     * @param  ClientContract  $clientContract
     * @param  CarOptionContract  $carOptionContract
     * @param  OrderTypeContract  $orderTypeContract
     * @param  OrderStatusContract  $orderStatusContract
     * @param  OrderOnWayRoadContract  $orderOnWayRoadContract
     * @param  OrderInProcessRoadContract  $inProcessRoadContract
     * @param  TariffServiceContract  $tariffService
     * @param  OrderStageCordContract  $stageContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  RatingPointServiceContract  $ratingService
     * @param  InitialOrderDataContract  $initialOrderContract
     * @param  DriverContract  $driverContract
     * @param  CarServiceContract  $carService
     * @param  OrderProcessContract  $processContract
     * @param  CompletedOrderContract  $completedOrderContract
     * @param  OrderWorkerCommentContract  $commentContract
     * @param  PreorderContract  $preOrderContract
     * @param  OrderCommonContract  $commonContract
     * @param  WaybillContract  $waybillContract
     * @param  OrderCorporateContract  $orderCorporateContract
     */
    public function __construct(
        protected OrderContract $orderContract,
        protected CorporateClientContract $corporateClientContract,
        protected PaymentTypeContract $paymentTypeContract,
        protected CarClassContract $carClassContract,
        protected ClientContract $clientContract,
        protected CarOptionContract $carOptionContract,
        protected OrderTypeContract $orderTypeContract,
        protected OrderStatusContract $orderStatusContract,
        protected OrderOnWayRoadContract $orderOnWayRoadContract,
        protected OrderInProcessRoadContract $inProcessRoadContract,
        protected TariffServiceContract $tariffService,
        protected OrderStageCordContract $stageContract,
        protected OrderShippedDriverContract $shippedContract,
        protected RatingPointServiceContract $ratingService,
        protected InitialOrderDataContract $initialOrderContract,
        protected DriverContract $driverContract,
        protected CarServiceContract $carService,
        protected OrderProcessContract $processContract,
        protected CompletedOrderContract $completedOrderContract,
        protected OrderWorkerCommentContract $commentContract,
        protected PreorderContract $preOrderContract,
        protected OrderCommonContract $commonContract,
        protected WaybillContract $waybillContract,
        protected OrderCorporateContract $orderCorporateContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getOrderForOperator($order_id): ?object
    {
        return $this->orderContract->with($this->operatorOrderRelations())->find($order_id);
    }

    /**
     * @param $data
     * @return array
     */
    public function checkClientLimit($data): array
    {
        $client_phone = $data['client_phone'];
        $company_id = $data['company_id'];
        $client = $this->clientContract
            ->where('phone', '=', $client_phone)->with([
                'corporate' => fn($q) => $q->where('company_id', '=', $company_id)->select(['client_id', 'limit']),
                'orders' => fn($q) => $q
                    ->select(['client_id', 'order_id'])
                    ->has('completed')
                    ->with(['completed' => fn($query) => $query->select(['order_id', 'cost'])])
            ])
            ->findFirst(['client_id']);

        $limit = $client['corporate'][0]['limit'];
        $costs = 0;

        foreach ($client['orders'] as $key) {
            $cost = $key['completed']['cost'];
            $costs += $cost;
        }

        return $limit < $costs ? [
            'message' => trans('messages.order_limit_corporate'),
            'status' => 0
        ] : ['message' => '', 'status' => 1];
    }

    /**
     * @inheritdoc
     */
    public function getOrderForDispatcher($order_id): ?object
    {
        return $this->orderContract->with($this->dispatcherOrderRelations())->find($order_id);
    }

    /**
     * @inheritDoc
     */
    public function getOrderInfo($id): array
    {
        $options = $this->carOptionContract->findAll();
        $paymentType = $this->paymentTypeContract->findAll();
        $carClass = $this->carClassContract->findAll();
        $client = $this->corporateClientContract->find($id)->client;
        $addresses = $client->addresses()->where('company_id', $this->corporateClientContract->find($id)->company_id)->get();

        return compact('options', 'paymentType', 'carClass', 'addresses');
    }

    /**
     * @inheritdoc
     */
    public function callCenterOrderPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] ?: 10;
        $page = $request->page ?: 1;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : '';

        ($request->status && is_numeric($request->status)) ? $this->orderContract->where('status_id', '=', $request->status) : null;
        ($request->type && is_numeric($request->type)) ? $this->orderContract->where('order_type_id', '=', $request->type) : null;

        return $this->orderContract
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->where(fn($query) => $query
                ->whereHasMorph('customer', [SystemWorker::class], fn($query) => $query->where('system_worker_id', '=', get_user_id()))
                ->orWhere('operator_id', '=', get_user_id())
            )
            ->when($search, fn($q) => $q
                ->whereHas('client', fn($q) => $q->where('phone', 'LIKE', '%'.$search.'%'))
                ->orWhereRaw("CONCAT(`address_from`, ' ', `address_to`) LIKE '%".$search."%'")
            )
            ->orderBy('order_id', 'desc')
            ->with($this->operatorOrderRelations())
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function callCenterDispatcherOrderPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] ?: 10;
        $page = $request->page ?: 1;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : '';
        $date_start = ($request->date_start && 'null' !== $request->date_start) ? $request->date_start : '';
        $date_end = ($request->date_end && 'null' !== $request->date_end) ? $request->date_end : '';

        ($request->status && is_numeric($request->status)) ? $this->orderContract->where('status_id', '=', $request->status) : null;
        ($request->type && is_numeric($request->type)) ? $this->orderContract->where('order_type_id', '=', $request->type) : null;

        return $this->orderContract
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->when($search, fn(Builder $q) => $q
                ->whereHas('client', fn(Builder $q) => $q->where('phone', 'LIKE', '%'.$search.'%'))
                ->orWhereRaw("CONCAT(`address_from`, ' ', `address_to`) LIKE '%".$search."%'")
            )
            ->when($date_start || $date_end, fn($query) => $query
                ->where('created_at', '>=', $date_start)
                ->where('created_at', '<=', $date_end))
            ->with($this->dispatcherOrderRelations())
            ->orderBy('order_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $client_id
     * @return Collection
     */
    public function getLastOrders($client_id): Collection
    {
        return $this->orderContract
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->where('client_id', '=', $client_id)
            ->with($this->dispatcherOrderRelations())
            ->orderBy('order_id', 'desc')
            ->limit(10)
            ->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getOrderTypes(): array|Collection
    {
        return $this->orderTypeContract->findAll(['order_type_id', 'name', 'text']) ?: collect();
    }

    /**
     * @return Collection
     */
    public function getOrderStatuses(): Collection
    {
        return $this->orderStatusContract->findAll(['order_status_id', 'status', 'name', 'text', 'color']) ?: collect();
    }

    /**
     * @inheritDoc
     */
    public function operatorPendingOrders(): Collection
    {
        return $this->orderContract
            ->whereHas('status', fn(Builder $q) => $q->where('status', '=', 1))
            ->whereHas('franchise', fn(Builder $q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->with($this->operatorOrderRelations())
            ->findAll([
                'order_id',
                'car_class_id',
                'order_type_id',
                'payment_type_id',
                'status_id',
                'client_id',
                'passenger_id',
                'operator_id',
                'customer_id',
                'customer_type',
                'franchisee',
                'car_option',
                'from_coordinates',
                'to_coordinates',
                'address_from',
                'address_to',
                'lat',
                'lut',
                'show_cord',
                'comments',
                'created_at'
            ])
            ?:
            collect();
    }

    /**
     * @inheritDoc
     */
    public function dispatcherPendingOrders(): Collection
    {
        return $this->orderContract
            ->whereHas('status', fn(Builder $q) => $q->where('status', '=', 1))
            ->whereHas('franchise', fn(Builder $q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->with($this->dispatcherOrderRelations())
            ->findAll([
                'order_id',
                'car_class_id',
                'order_type_id',
                'payment_type_id',
                'status_id',
                'client_id',
                'passenger_id',
                'operator_id',
                'customer_id',
                'customer_type',
                'franchisee_id',
                'car_option',
                'from_coordinates',
                'to_coordinates',
                'address_from',
                'address_to',
                'lat',
                'lut',
                'show_cord',
                'comments',
                'created_at'
            ])
            ?: collect();
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function createOrderProcessRoutes($shipment_driver_id, array $routes, bool $on_way = false): ?array
    {
        $contract = $on_way ? $this->orderOnWayRoadContract : $this->inProcessRoadContract;

        $completed_route = [];
        $created = [];

        foreach ($routes as $route) {
            $count = floor(count($route['points']));
            $chunk_count = $count >= 16 ? $count / 4 : 1;
            $chunk_array = array_chunk($route['points'], (int)floor($chunk_count));

            foreach ($chunk_array as $chunks) {
                foreach ($chunks as $point) {
                    $completed_route[] = decode(['lat' => $point['lat'], 'lut' => $point['lut']]);
                }
            }

            $created[] = $contract->create(
                [
                    'route' => decode($completed_route),
                    'duration' => $route['duration'],
                    'distance' => $route['distance'],
                    'shipment_driver_id' => $shipment_driver_id,
                ]
            )->toArray();
        }

        if (!$created) {
            return null;
        }

        return $created;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentTypes(): array|Collection
    {
        return $this->paymentTypeContract->findAll(['payment_type_id', 'type', 'name', 'text']);
    }

    /**
     * @inheritDoc
     */
    public function getCarOptions(): Collection
    {
        return $this->carOptionContract->findAll(['car_option_id', 'option', 'price']);
    }

    /**
     * @inheritDoc
     */
    public function getCarClasses(): array|Collection
    {
        return $this->carClassContract->findAll() ?? [];
    }

    /**
     * @inheritDoc
     * @noinspection MultipleReturnStatementsInspection
     */
    public function getOrderDriver($order_id, array $values = ['*']): ?Driver
    {
        $order = $this->orderContract
            ->with([
                'ordering_shipments' => fn(HasMany $shipment_query) => $shipment_query->whereHas('status', fn(Builder $query) => $query
                    ->where('status', '=', OrderShippedStatus::PRE_ACCEPTED)
                    ->orWhere('status', '=', OrderShippedStatus::PRE_PENDING)
                )
            ])
            ->find($order_id);

        if ($order->ordering_shipments->count() <= 0) {
            return null;
        }

        $shipment = $order->ordering_shipments->load('driver');
        $driver = $shipment->first()->driver;

        if (!$driver) {
            return null;
        }

        return $driver;
    }

    /**
     * @param $order_id
     * @param  bool  $response
     * @return bool
     * @throws OrderCanceledInSearchDriverException
     */
    public function orderHasCanceled($order_id, bool $response = false): bool
    {
        $canceled = $this->orderContract
            ->where($this->orderContract->getKeyName(), '=', $order_id)
            ->has('canceled')
            ->exists();

        if ($canceled) {
            if ($response) {
                return true;
            }

            throw new OrderCanceledInSearchDriverException('Order Canceled');
        }

        return false;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function setStages($stage, $values, $order_id, $date_name): bool
    {
        $order = $this->orderContract->find($order_id, ['order_id']);

        if ('pause' === $stage) {
            $pause_cord = $this->stageContract->findBy('order_id', $order_id);

            if ($pause_cord->start) {
                $new_cord[] = $pause_cord->start;
                $new_cord[] = $values;
                $values = $new_cord;
            }
        }

        $staged = $this->stageContract->updateOrCreate(
            ['order_id', '=', $order->order_id],
            [$stage => decode($values), $date_name => now()->format('Y-m-d H:i:s')]
        );

        if (!$staged) {
            return false;
        }

        return true;
    }

    /**
     * @todo merge one method with prepareCommonOrder
     * @inheritDoc
     */
    public function createOrderForDriver($driver_id, $order_id, $from, $distance = null, $duration = null, array $points = []): array
    {
        $from = $this->orderContract->find($order_id, ['order_id', 'address_from'])->address_from ?: $from;
        $rat = $this->ratingService->calculateRating($driver_id, $order_id, $distance);
        $order = $this->orderContract
            ->with([
                'company' => fn($query) => $query->select(['companies.company_id', 'companies.name']),
                'tariff' => fn($query) => $query
                    ->has('rent')
                    ->with(['rent' => fn($query) => $query->select(['tariff_id', 'hours'])])
                    ->select(['tariff_id'])
            ])
            ->find($order_id, ['order_id', 'comments']);

        $this->shippedContract
            ->where('order_id', '=', $order_id)
            ->whereHas(
                'status',
                fn(Builder $status_query) => $status_query->where('status', '=', OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_PENDING))
            )
            ->updateSet(['status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_REJECTED), 'current' => 0]);

        $order_response_url_hash = get_token();

        $shipped = $this->shippedContract->create([
            'driver_id' => $driver_id,
            'order_id' => $order_id,
            'estimated_rating_id' => $rat['estimated_rating_id'],
            'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_PENDING),
            'current' => true,
            'accept_hash' => $order_response_url_hash,
        ]);

        return [
            'order_id' => $order_id,
            'cash' => !$order->company,
            'rent' => (bool)($order->tariff->rent ?? false),
            'rent_hour' => $order->tariff->rent->hours ?? 0,
            'comment' => $order->comments,
            'company_name' => $order->company->name ?? '',
            'shipped_id' => $shipped->order_shipped_driver_id,
            'accept_hash' => $order_response_url_hash,
            'rating_rejected' => $rat['rejected'],
            'rating_accepted' => $rat['accepted'],
            'address_from' => $from,
            'distance' => $distance,
            'duration' => $duration,
            'points' => $points,
            'delivery_time' => $this->orderDeliveryTime($order_id),
            'rating' => $rat,
        ];
    }

    /**
     * @inheritdoc
     */
    #[ArrayShape([
        'order_id' => 'int',
        'cash' => 'bool',
        'company_name' => 'mixed',
        'rating_rejected' => 'mixed',
        'rating_accepted' => 'mixed',
        'delivery_time' => 'int',
        'address_from' => 'array'
    ])] public function createDriverCommonList(int $driver_id, int $order_id, bool $current = false): ?array
    {
        $rating = $this->ratingService->calculateRating($driver_id, $order_id);

        $order = $this->orderContract
            ->with(['company' => fn($query) => $query->select(['companies.company_id', 'name'])])
            ->find($order_id, ['order_id', 'address_from', 'from_coordinates']);

        return [
            'order_id' => $order_id,
            'cash' => !$order->company,
            'company_name' => $order->company->company_name ?? '',
            'rating_rejected' => $rating['rejected'],
            'rating_accepted' => $rating['accepted'],
            'delivery_time' => $this->orderDeliveryTime($order_id),
            'address_from' => $order->address_from,
            'cord_from' => $order->from_coordimnates,
        ];
    }

    /**
     * @inheritDoc
     */
    public function orderFromToPrice(Model $client, array $coordinates = [], array $options = [], array $times = []): ?array
    {
        $tariff_ids = $this->tariffService->getTariff($coordinates['from'], $coordinates['to'], $options);

        if (!$tariff_ids) {
            return null;
        }

        $price = $this->tariffService->getPriceByTariff($tariff_ids, $coordinates['from'], $coordinates['to'], $options);
        $this->setInitialOrderData($tariff_ids, $price, $client, $coordinates['from']);

        return $price;
    }

    /**
     * @param $tariffs
     * @param  array  $price_data
     * @param  Model  $client
     * @param $coordinates
     * @param  null  $order_id
     */
    public function setInitialOrderData($tariffs, array $price_data, Model $client, $coordinates, $order_id = null): void
    {
        $locality = session('order');

        $this->initialOrderContract
            ->updateOrCreate(
                ['orderable_id', '=', $client->{$client->getKeyName()}, 'orderable_type', '=', $client->getMap(), 'order_id', '=', $order_id],
                [
                    'orderable_id' => $client->{$client->getKeyName()},
                    'orderable_type' => $client->getMap(),
                    'initial_tariff_id' => $tariffs['from_tariff'],
                    'second_tariff_id' => $tariffs['to_tariff'],
                    'behind' => $tariffs['behind'] ?? 0,
                    'price' => $price_data['coin'],
                    'sitting_price' => $price_data['sitting_price'] ?? 0,
                    'option_price' => $price_data['option_price'] ?? 0,
                    'currency' => $price_data['currency'],
                    'sitting_fee' => $price_data['sitting_fee'] ?? 0,
                    'initial' => $price_data['initial'] ?? false,
                    'distance' => $price_data['distance'] ?? 0,
                    'duration' => $price_data['time'] ?? 0,
                    'region_id' => $locality['from_region_id'],
                    'city_id' => $locality['from_city_id'] ?? null,
                    'lat' => $coordinates[0],
                    'lut' => $coordinates[1],
                ]
            );
    }

    /**
     * @inheritDoc
     * @throws BindingResolutionException
     */
    public function orderFromToPrices(Model $client, array $cord = [], array $options = [], array $times = [], bool $rent = false): ?array
    {
        $price_tariff = $this->existedPriceRequest($cord, $options, $rent);

        if ($price_tariff && [$prices, $tariff] = $price_tariff) {
            $this->redis()->hSet(ConstRedis::order_calc_response($client->{$client->getKeyName()}), 'prices', igs($prices));

            if (isset($tariff[$options['car_class']])) {
                $this->setInitialOrderData($tariff[$options['car_class']], $prices[$options['car_class']], $client, $cord['from']);
            }

            return $prices;
        }

        // DISPATCH JOB IN SERVICE
        $this->getTaxisByFromRadius($cord['from'][0], $cord['from'][1], user());

        $cars_classes = $this->carService->getCarClassesWithMinPrice($cord['from'], $options['payment_type_company'], $options, $rent);
        $selected_class = $options['car_class'];

        $initial_tariff = [];
        $prices = [];

        foreach ($cars_classes as $c_class) {
            $options['car_class'] = $c_class->car_class_id;
            $tariff = $this->tariffService->getTariff($cord['from'], $cord['to'], $options, $rent);

            if (!$tariff) {
                continue;
            }

            $tariff_ids[$c_class->car_class_id] = $tariff;
            $prices[$c_class->car_class_id] = $this->tariffService->getPriceByTariff($tariff_ids[$c_class->car_class_id], $cord['from'], $cord['to'], $options, $rent);
            $prices[$c_class->car_class_id]['is_rent'] = $rent;
            $prices[$c_class->car_class_id]['rent_time'] = $options['rent_time'];
            $prices[$c_class->car_class_id]['class_id'] = $c_class->car_class_id;
            $prices[$c_class->car_class_id]['image'] = $c_class->image;
            $prices[$c_class->car_class_id]['name'] = $c_class->class_name;
            $prices[$c_class->car_class_id]['selected_class'] = $selected_class;
            $prices[$c_class->car_class_id]['options'] = $this->carService->getOptions($c_class->car_class_id,
                $tariff_ids[$c_class->car_class_id]['from_tariff']);
            $prices[$c_class->car_class_id]['rent_times'] = $this->tariffService->getRentTimesByData($c_class->car_class_id);

            if ($tariff_ids) {
                $initial_tariff = $tariff_ids[$selected_class] ?? $tariff_ids[array_key_first($prices)];
            }
        }

        if (!$prices) {
            return null;
        }

        $this->setInitialOrderData($initial_tariff, $prices[$selected_class] ?? $prices[array_key_first($prices)], $client, $cord['from']);
        $this->redis()->hMSet(ConstRedis::order_calc_response($client->{$client->getKeyName()}), [
            'prices' => igs($prices),
            'tariff' => igs($tariff_ids)
        ]);
        $this->redis()->expire(ConstRedis::order_calc_response($client->{$client->getKeyName()}), 15 * 60);

        $price = array_column($prices, 'class_id');
        array_multisort($price, SORT_ASC, $prices);

        return $prices;
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     */
    public function driverOrderPause($driver_id, $pause_hash): int
    {
        $cord_updated = f_now();

        $driver = $this->driverContract
            ->with([
                'current_order' => fn($order) => $order->select(['orders.order_id', 'orders.status_id', 'client_id']),
                'initial_order_data' => fn($initial) => $initial->select(['order_initial_data_id', 'initial_tariff_id', 'second_tariff_id']),
                'order_client' => fn($client) => $client->select(['clients.client_id', 'phone']),
                'process' => fn($process) => $process->select([
                    'order_process_id',
                    'order_shipped_id',
                    'cord_updated',
                    'pause_time',
                    'travel_time',
                    'total_price',
                    'pause_price'
                ]),
            ])
            ->find($driver_id, ['driver_id', 'phone', 'car_id', 'current_franchise_id', 'lat', 'lut', 'azimuth']);

        if (!$driver && !$driver->current_order) {
            throw new Lexcept('Error you are not in order', 422);
        }

        if ($this->orderContract->find($driver->current_order->order_id, ['order_id', 'status_id'])->status_id === OrderStatus::ORDER_PAUSED) {
            $this->orderContract->update($driver->current_order->{$driver->current_order->getKeyName()}, ['status_id' => OrderStatus::ORDER_IN_PROCESS]);
            $this->processContract->update($driver->process->{$driver->process->getKeyName()}, ['cord_updated' => $cord_updated]);

            return OrderStatus::ORDER_IN_PROCESS;
        }

        $this->stageContract
            ->where('order_id', '=', $driver->current_order->order_id)
            ->update(DB::update('
                    update order_stages_cord set
                    pauses=JSON_ARRAY_APPEND(pauses, \'$\', CAST(\'{"lat": "'.$driver->lat.'", "lut": "'.$driver->lat.'", "paused": "'.$cord_updated.'"}\' AS JSON))
                ')
            );

        $this->orderContract->update($driver->current_order->{$driver->current_order->getKeyName()}, ['status_id' => OrderStatus::ORDER_PAUSED]);
        PauseOrder::dispatch($driver, $pause_hash)->onQueue(ConstQueue::LONG()->getValue());

        return OrderStatus::ORDER_PAUSED;
    }

    /**
     * @param $request
     * @return bool
     */
    public function slipUpdate($request): bool
    {
        $order = $this->orderContract
            ->where('order_id', '=', $request->order_id)
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->has('completed')
            ->has('corporate')
            ->with(['completed' => fn($query) => $query->select(['completed_order_id', 'order_id', 'driver_id'])])
            ->findFirst(['order_id', 'franchisee']);

        if (!$order) {
            return false;
        }

        $_corporate = $this->orderCorporateContract
            ->where('order_id', '=', $order->order_id)
            ->updateSet(['driver_id' => $order->completed->driver_id, 'slip_number' => $request->slip_number, 'order_id' => $order->order_id]);

        if (!$_corporate) {
            return false;
        }

        return true;
    }

    /**
     * @param  int  $order_id
     * @param  string  $comment
     * @param  bool  $for_driver
     * @return bool|Collection
     */
    public function orderCommentCreate(int $order_id, string $comment, bool $for_driver = false): bool|Collection
    {
        $order = $this->orderContract
            ->where('order_id', '=', $order_id)
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->exists('order_id');

        if (!$order) {
            return false;
        }

        $_comment = $this->commentContract->create([
            'text' => $comment,
            'system_worker_id' => user()->system_worker_id,
            'order_id' => $order_id,
            'driver' => $for_driver
        ]);

        if (!$_comment) {
            return false;
        }

        if ($for_driver) {
            $order = $this->orderContract
                ->with(['driver' => fn($query) => $query->select(['drivers.driver_id', 'car_id', 'phone', 'current_franchise_id'])])
                ->find($order_id, ['order_id']);

            if ($order->driver) {
                SendNotifies::dispatch($order->driver->driver_id, $order->driver->getMap(), get_user_id(), 'Comment', $comment);
            }
        }

        return $this->commentContract
            ->where('order_id', '=', $order_id)
            ->with(['worker' => fn($query) => $query->select(['system_worker_id', 'name', 'surname', 'photo', 'phone', 'patronymic'])])
            ->findAll(['created_at', 'order_id', 'order_worker_comment_id', 'system_worker_id', 'text']);
    }

    /**
     * @param $request
     * @return array|null
     */
    public function callCenterOrderFeedbackCreate($request): ?array
    {
        $order = $this->orderContract
            ->where('order_id', '=', $request->order_id)
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->findFirst(['order_id']);

        if (!$order) {
            return null;
        }

        $type = true;

        switch ($request->type) {
            case 'driver':
                if (!$this->callCenterDriverFeedbackCreate($request, $order)) {
                    $type = false;
                    break;
                }
                break;
            case 'client':
                if (!$this->callCenterClientFeedbackCreate($request, $order)) {
                    $type = false;
                    break;
                }
                break;
            case 'worker':
                if (!$this->callCenterWorkerComplaintCreate($request, $order)) {
                    $type = false;
                    break;
                }
                break;
            default:
        }

        if (!$type) {
            return null;
        }

        $feedbacks = $order->feedbacks->load(['option', 'writable']);
        $complaints = $order->complaints->load(['writer', 'recipient']);

        return compact('feedbacks', 'complaints');
    }

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @return array|null
     * @todo merge one method with createOrderForDriver
     */
    #[ArrayShape([
        'order_id' => 'int',
        'shipped_id' => 'mixed',
        'accept_hash' => 'string',
        'rating_rejected' => 'mixed',
        'rating_accepted' => 'mixed',
        'delivery_time' => 'int',
        'rating' => 'array',
        'address_from' => 'array'
    ])] public function prepareCommonOrder(int $order_id, int $driver_id): ?array
    {
        $token = get_token();

        $rating = $this->ratingService->calculateRating($driver_id, $order_id);

        $order = $this->orderContract
            ->with(['preorder' => fn($query) => $query->select(['preorder_id', 'order_id', 'diff_minute'])])
            ->find($order_id, ['order_id', 'address_from', 'from_coordinates']);

        if (!$order) {
            return null;
        }

        $this->shippedContract->updateOrCreate(['order_id', '=', $order_id, 'driver_id', '=', $driver_id], [
            'driver_id' => $driver_id,
            'order_id' => $order_id,
            'accept_hash' => $token,
            'common' => true,
            'current' => !$order->preorder,
            'estimated_rating_id' => $rating['estimated_rating_id']
        ]);

        $shipped = $this->shippedContract
            ->where('order_id', '=', $order_id)
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['order_shipped_driver_id']);

        return [
            'order_id' => $order_id,
            'shipped_id' => $shipped->order_shipped_driver_id,
            'accept_hash' => $token,
            'rating_rejected' => $rating['rejected'],
            'rating_accepted' => $rating['accepted'],
            'delivery_time' => $this->orderDeliveryTime($order_id),
            'rating' => $rating,
            'address_from' => $order->address_from,
            'cord_from' => $order->from_coordinates,
        ];
    }

    /**
     * @inheritDoc
     */
    public function commonOrderAcceptStatuses(int $order_id, int $driver_id): bool
    {
        $preorder = false;
        $now = now();

        if ($this->orderContract->has('preorder')->find($order_id, ['order_id'])) {
            $preorder = true;
        }

        $this->commonContract
            ->where('order_id', '=', $order_id)
            ->where('accept', '=', false)
            ->whereJsonContains('driver->ids', $driver_id)
            ->updateSet(['accept' => true, 'emergency' => false, 'accepted' => $now]);

        return $preorder;
    }

    /**
     * @param $driver_id
     * @return array
     */
    public function getWaybillDistanceWithPrice($driver_id): array
    {
        $now = now();

        $waybill = $this->waybillContract
            ->where('driver_id', '=', $driver_id)
            ->whereDate('start_time', '<=', $now->format('Y-m-d H:i:s'))
            ->whereDate('end_time', '>=', $now->format('Y-m-d H:i:s'))
            ->findFirst();

        if (!$waybill) {
            return [0, 0.0];
        }

        $completed = $this->completedOrderContract
            ->whereHas('order', fn($query) => $query
                ->whereDate('created_at', '>=', $waybill->start_time)
                ->whereDate('created_at', '<=', $waybill->end_time)
            )
            ->where('driver_id', '=', $driver_id)
            ->findAll(['completed_order_id', 'driver_id', 'cost', 'distance'])
            ->pipe(fn(Collection $item) => ['distance' => $item->sum('distance'), 'cost' => $item->sum('cost')]);

        return [$completed['distance'], $completed['cost']];
    }

    /**
     * @inheritDoc
     */
    public function repeatOrder($order_id): bool
    {
        $data = $this->redis()->hMGet(ConstRedis::order_create_data($order_id), ['price_data', 'order_data']);

        /** @noinspection PhpArrayIsAlwaysEmptyInspection */
        if (!$data && !($data[0] || $data[1])) {
            return false;
        }

        $order = $this->orderContract->find($order_id);
        $price_data = igus($data[0]);
        $order_data = igus($data[1]);

        $this->afterOrderCreate($order_data, $order, $price_data);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function afterOrderCreate(array $order_data, $order, array $price_data = null): Collection
    {
        $ordered = [
            'id' => $order['client_id'],
            'type' => $this->clientContract->getMap()
        ];

        $this->orderContract->where('order_id', '=', $order['order_id'])
            ->updateSet(['repeat_at', $order['created_at']]);

        $this->clientContract->update($order->client_id, ['in_order' => true]);

        $this->initialOrderContract
            ->where('orderable_id', '=', $ordered['id'])
            ->where('orderable_type', '=', $ordered['type'])
            ->where('order_id', '=')
            ->updateSet(['order_id' => $order->order_id]);

        $price = $price_data ?: $this->getPrice($order_data);

        $additional_params = [
            'preorder' => $order_data['preorder'],
            'meet' => $order_data['meet'],
            'passenger' => $order_data['passenger'],
            'corporate' => $order_data['corporate']
        ];

        $this->redis()->hMSet(ConstRedis::order_create_data($order->order_id), [
            'price_data' => igs($price),
            'order_data' => igs($order_data)
        ]);

        OrderDistributor::dispatch($order, $additional_params, $price['coin'])->onQueue(ConstQueue::LONG()->getValue());

        return $this->parseResult($instance, ['order', 'price', 'schedule'], [$order->getAttributes(), $price, $order_data['preorder']]);
    }

    /**
     * @param $order_id
     * @return array
     */
    public function viewOrderInfo($order_id): array
    {
        $order = $this->orderContract
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->with($this->viewOrderRelations())
            ->find($order_id);

        $history = $this->orderHistory($order_id);

        return compact('order', 'history');
    }

    /**
     * @inheritDoc
     */
    public function orderHistory($order_id): ?object
    {
        return $this->orderContract->orderHistory($order_id);
    }

    public function setRepeatedAt(int $order_id)
    {
        return $this->orderContract->update($order_id, ['repeated_at' => Carbon::now()->format('d-m-Y H:i:s')]);
    }
}
