<?php

declare(strict_types=1);


namespace Src\Services\Driver;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use ReflectionException;
use ServiceEntity\BaseService;
use Src\Broadcasting\Broadcast\Client\DriverOnAcceptOrderEvent;
use Src\Broadcasting\Broadcast\Client\OrderReset;
use Src\Broadcasting\Notifications\ClientNotify;
use Src\Core\Complex\GetDriverState;
use Src\Core\Complex\UpdateDriversCord;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstRatingPattern;
use Src\Core\Enums\ConstShippedStatus;
use Src\Exceptions\Lexcept;
use Src\Exceptions\UnauthorizedException;
use Src\Http\Resources\Driver\DriverMapViewResource;
use Src\Jobs\OrderCommons\WaitingPriceCalcQue;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\OrderCommon;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Car\CarContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverContract\DriverContractContract;
use Src\Repositories\DriverInfo\DriverInfoContract;
use Src\Repositories\DriverType\DriverTypeContract;
use Src\Repositories\DriverTypeOptional\DriverTypeOptionalContract;
use Src\Repositories\EstimatedRating\EstimatedRatingContract;
use Src\Repositories\FranchiseTransaction\FranchiseTransactionContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderFeedback\OrderFeedbackContract;
use Src\Repositories\OrderInProcessRoad\OrderInProcessRoadContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\OrderEnd\OrderEndServiceContract;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\RatingPointService\RatingPointServiceContract;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * Class DriverService
 * @package Src\Services\Driver
 */
final class DriverService extends BaseService implements DriverServiceContract
{
    use DriverTrait;

    /**
     * @param  DriverContract  $driverContract
     * @param  OrderContract  $orderContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  OrderInProcessRoadContract  $processRoadContract
     * @param  ClientContract  $clientContract
     * @param  OrderServiceContract  $orderService
     * @param  OrderEndServiceContract  $orderEndService
     * @param  ClientServiceContract  $clientService
     * @param  GeocodeServiceContract  $geoService
     * @param  DriverTypeContract  $driverTypeContract
     * @param  RatingPointServiceContract  $ratingService
     * @param  OrderFeedbackContract  $feedbackContract
     * @param  DriverTypeOptionalContract  $driverTypeOptionalContract
     * @param  PaymentServiceContract  $paymentService
     * @param  EstimatedRatingContract  $estimatedRatingContract
     * @param  OrderProcessContract  $orderProcessContract
     * @param  DriverInfoContract  $driverInfoContract
     * @param  PreorderContract  $preorderContract
     * @param  FranchiseTransactionContract  $transactionContract
     * @param  DriverContractContract  $driverContractContract
     * @param  CarContract  $carContract
     * @param  OrderCommonContract  $commonContract
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected OrderContract $orderContract,
        protected OrderShippedDriverContract $shippedContract,
        protected OrderInProcessRoadContract $processRoadContract,
        protected ClientContract $clientContract,
        protected OrderServiceContract $orderService,
        protected OrderEndServiceContract $orderEndService,
        protected ClientServiceContract $clientService,
        protected GeocodeServiceContract $geoService,
        protected DriverTypeContract $driverTypeContract,
        protected RatingPointServiceContract $ratingService,
        protected OrderFeedbackContract $feedbackContract,
        protected DriverTypeOptionalContract $driverTypeOptionalContract,
        protected PaymentServiceContract $paymentService,
        protected EstimatedRatingContract $estimatedRatingContract,
        protected OrderProcessContract $orderProcessContract,
        protected DriverInfoContract $driverInfoContract,
        protected PreorderContract $preorderContract,
        protected FranchiseTransactionContract $transactionContract,
        protected DriverContractContract $driverContractContract,
        protected CarContract $carContract,
        protected OrderCommonContract $commonContract
    ) {
    }

    /**
     * @inheritDoc
     * @param  int  $driver_id
     * @param  array  $data
     * @return bool|null
     * @throws Lexcept
     */
    public function driverOrderReady(int $driver_id, array $data): ?bool
    {
        if (!$this->driverContract->update($driver_id, ['is_ready' => $data['ready']])) {
            throw new Lexcept('Server error', 500);
        }

        return true;
    }

    /**
     * @param  int  $driver_id
     * @return Collection
     */
    public function getBelatedCommons(int $driver_id): Collection
    {
        return $this->commonContract
            ->where('active', '=', true)
            ->where('accept', '=', false)
            ->where('manual', '=', false)
            ->where(fn(Builder $query) => $query
                ->where('filter_type', '=', OrderCommon::TYPE_ALL)
                ->orWhere('filter_type', '=', OrderCommon::TYPE_ONLINE)
            )
            ->with([
                'order' => fn($query) => $query
                    ->with(['preorder' => fn($query) => $query->select('order_id', 'time')])
                    ->select(['order_id', 'address_from', 'from_coordinates']),
                'initial' => fn($query) => $query
                    ->select(['order_id', 'price']),
                'preorder' => fn($query) => $query
                    ->select(['order_id', 'time', 'preorder_id']),
                'company' => fn($query) => $query
                    ->select(['companies.company_id', 'name']),
            ])
            ->findAll(['order_id', 'driver', 'order_common_id'])
            ->each(function ($item) {
                if (!$item->order) {
                    return;
                }

                $item->order_id = $item->order->order_id;
                $item->delivery_time = $item->preorder ? $item->preorder->time : 0;
                $item->address_from = $item->order->address_from;
                $item->cord_from = $item->order->from_coordinates;
                $item->cash = $item->initial->price;
                $item->company_name = $item->company->name ?? '';
            });
    }

    /**
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  int  $azimuth
     * @param  int  $speed
     * @param  int  $driver_id
     * @return void
     * @throws ReflectionException
     */
    public function updateCurrentCoordinates(float $latitude, float $longitude, int $azimuth, int $speed, int $driver_id): void
    {
        if ($this->driverContract->where('is_ready', '=', false)->find($driver_id, ['driver_id', 'is_ready'])) {
            return;
        }

        UpdateDriversCord::complex($driver_id, $latitude, $longitude, $azimuth, $speed);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function driverOnWay($order_id, $hash, $driver_id, int $selected_route_id = null, bool $accept = true): bool|string|array
    {
        if ($this->orderContract->has('preorder')->find($order_id, ['order_id'])) {
            return $this->declineCommonOrder($driver_id, $order_id, $hash, $accept);
        }

        return $this->regularOnWay($driver_id, $order_id, $hash, $selected_route_id);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function driverShippedOrder($hash, $order_id, $driver_id, bool $accept): ?array
    {
        if (!$accept) {
            $order_rating = $this->orderEndService->driverShippedOrderReject($driver_id, $order_id, $hash);
            $this->driverLocked($driver_id);

            return ['reject' => $order_rating->remove_rating ?? true, 'common_reject' => $order_rating->common_rejected ?? true];
        }

        $cord = $this->driverContract->getCordArray($driver_id);
        $this->orderService->setStages('accept', $cord, $order_id, 'accepted');

        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);
        $driver = $this->getDriverUpdatedDriverData($driver_id);

        DriverOnAcceptOrderEvent::broadcast($client, new DriverMapViewResource($driver), $hash, trans('messages.driver_accepted_order'));
        Notification::send($client, new ClientNotify($driver));

        return $this->driverAcceptedOrder($hash, $cord);
    }

    /**
     * @inheritDoc
     */
    public function getDriverUpdatedDriverData($driver_id): array
    {
        $driver = $this->driverContract
            ->with([
                'car' => fn(BelongsTo $car_query) => $car_query
                    ->select(['car_id', 'current_driver_id', 'class', 'mark', 'model', 'color', 'sts_number', 'state_license_plate']),
                'driver_info' => fn(BelongsTo $info_query) => $info_query
                    ->select(['driver_info_id', 'name', 'surname', 'photo'])
            ])
            ->find(
                $driver_id,
                [$this->driverContract->getKeyName(), 'car_id', 'selected_class', 'driver_info_id', 'current_franchise_id', 'lat', 'lut', 'azimuth', 'phone']
            );

        if (!$driver) {
            return [];
        }

        $driver->mergeAttributes($driver->driver_info)->all();

        return array_merge($driver->mergeAttributes($driver->driver_info)->except(['driver_info_id'])->all(), ['car' => $driver->car->getAttributes()]);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    #[ArrayShape([
        'routes' => 'array',
        'order_id' => 'mixed',
        'hash' => 'string',
        'free_wait_minute' => 'mixed',
        'paid_wait_minute' => 'mixed',
        'customer' => 'bool',
        'payment_type' => 'mixed',
        'to' => 'mixed',
        'to_cord' => 'mixed'
    ])]
    public function driverInPlace($order_id, $hash, $cord): ?array
    {
        $order = $this->shippedContract->where('in_place_hash', '=', $hash)
            ->with([
                'order' => fn($query) => $query->select([
                    'order_id',
                    'to_coordinates',
                    'address_to',
                    'address_from',
                    'from_coordinates',
                    'customer_type',
                    'payment_type_id'
                ]),
                'driver' => fn($query) => $query->select(['driver_id', 'current_status_id']),
                'initial_order_tariff' => fn($query) => $query->select(['tariff_id', 'free_wait_minutes', 'paid_wait_minute']),
                'payment_type' => fn($query) => $query->select(['*'])
            ])
            ->findFirst(['order_shipped_driver_id', 'order_id', 'driver_id']);

        $generate_hash = get_token();

        $this->driverContract->beginTransaction(function () use ($order, $generate_hash, $hash) {
            $shipped = $this->shippedContract
                ->where('in_place_hash', '=', $hash)
                ->updateSet(['in_order_hash' => $generate_hash]);

            if (!$shipped) {
                return false;
            }

            $driver = $this->driverContract->update($order->driver->driver_id,
                ['current_status_id' => DriverStatus::getStatusId(DriverStatus::DRIVER_IN_PLACE)]);

            if (!$driver) {
                return false;
            }
        });

        $this->orderService->setStages('in_place', $cord, $order_id, 'in_placed');

        $route = [];

        if ($order && $order->order->to_coordinates) {
            $routes = $this->geoService->roadCalculation($cord, $order->order->to_coordinates, null, false);
            $route_created = $this->orderService->createOrderProcessRoutes($order->order_shipped_driver_id, $routes);

            foreach ($route_created as $key => $value) {
                $route[$key] = [
                    'route' => $value['route'],
                    'duration' => $value['duration'],
                    'distance' => $value['distance'],
                    'order_in_process_road_id' => $value['order_in_process_road_id']
                ];
            }
        }

        return [
            'routes' => $route,
            'order_id' => $order->order_id,
            'hash' => $generate_hash,
            'free_wait_minute' => $order->initial_order_tariff->free_wait_minutes ?? 0,
            'paid_wait_minute' => $order->initial_order_tariff->paid_wait_minute ?? 0,
            'customer' => $order->order->customer_type === $this->clientContract->getMap(),
            'payment_type' => trans($order->payment_type->text),
            'from' => $order->order->address_from,
            'from_cord' => $order->order->from_coordinates,
            'to' => $order->order->address_to,
            'to_cord' => $order->order->to_coordinates,
        ];
    }

    /**
     * @param $hash_name
     * @param $hash
     * @param $driver_id
     * @return bool|mixed
     */
    public function deleteOnWayRoads($hash_name, $hash, $driver_id)
    {
        $on_way = $this->shippedContract
            ->where($hash_name, '=', $hash)
            ->where('driver_id', '=', $driver_id)
            ->with(['on_way_roads' => fn(HasMany $q_on_way) => $q_on_way->select(['order_on_way_road_id', 'shipment_driver_id'])])
            ->findFirst([$hash_name, 'order_shipped_driver_id', 'driver_id']);

        $deletes = false;

        foreach ($on_way->on_way_roads as $on_way_road) {
            $deletes = $on_way_road->delete();
        }

        return $deletes;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function responseInStartOrder($order_id, $hash, $driver_id, $route_or_lat = null, $lut = null): ?Collection
    {
        $pause_end_hash = $this->createOrderInProcessHashes($hash);

        $route = null;

        if (!$route_or_lat && !$lut) {
            $route = $this->inStartOrderWithoutData($hash, $driver_id);
        }

        if ($route_or_lat && !$lut) {
            $route = $this->inStartOrderWithRoute($hash, $route_or_lat);
        }

        $start_cord = $this->driverContract->getCordArray($driver_id);

        $this->startCordSave($hash, $driver_id, $start_cord, !$lut ? $route_or_lat : null);

        if ($route_or_lat && $lut) {
            $to_cords = ['lat' => $route_or_lat, 'lut' => $lut];
            $route = $this->inStartOrderWithCords($order_id, $hash, $driver_id, $start_cord, $to_cords);
        }

        if (null === $route) {
            return null;
        }

        $this->orderService->setStages('start', $start_cord, $order_id, 'started');
        WaitingPriceCalcQue::dispatch($hash, $order_id, now())->onQueue(ConstQueue::BASE()->getValue());

        $this->driverContract
            ->where($this->driverContract->getKeyName(), '=', $driver_id)
            ->updateSet(['current_status_id' => DriverStatus::getStatusId(DriverStatus::DRIVER_IN_ORDER)]);

        $order = $this->orderContract->find($order_id, ['order_id', 'to_coordinates', 'address_to']);
        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);

        return $this->parseResult(
            $route,
            ['client', 'pause_hash', 'end_hash', 'to', 'to_cord'],
            [$client, $pause_end_hash['pause_hash'], $pause_end_hash['end_hash'], $order->address_to ?? '', $order->to_coordinates ?? []]
        );
    }

    /**
     * @param $hash
     * @param $driver_id
     * @param $start_cord
     * @param  int|null  $selected_route_id
     * @throws JsonException
     */
    public function startCordSave($hash, $driver_id, $start_cord, ?int $selected_route_id = null): void
    {
        $start_cord['date'] = f_now();

        $shipped = $this->shippedContract
            ->where('in_order_hash', '=', $hash)
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['order_shipped_driver_id', 'driver_id', 'in_order_hash', 'order_id']);

        if (!$shipped) {
            return;
        }

        if ($selected_route_id && $shipped->has('in_process_roads')) {
            $road = $this->getProcessRoads($driver_id, $shipped->order_id, true);

            if ($road) {
                $this->processRoadContract->update($road->{$road->getKeyName()},
                    ['real_road' => decode([$start_cord])]);
                $this->orderProcessContract
                    ->whereHas('shipped', fn(Builder $query) => $query->where('order_shipped_driver_id', '=',
                        $shipped->{$shipped->getKeyName()}))
                    ->updateSet(['cord_updated' => now()]);
            }
        } else {
            $this->processRoadContract->updateOrCreate(
                ['shipment_driver_id', '=', $shipped->order_shipped_driver_id],
                [
                    'real_road' => decode([$start_cord]),
                    'shipment_driver_id' => $shipped->order_shipped_driver_id,
                    'selected' => 1
                ]
            );

            $this->orderProcessContract->updateOrCreate(['order_shipped_id', '=', $shipped->order_shipped_driver_id],
                ['cord_updated' => now()]);
        }
    }

    /**
     * @inheritDoc
     */
    public function getProcessRoads($driver_id, $order_id, bool $selected = false, array $values = []): ?object
    {
        return $this->processRoadContract
            ->where('selected', '=', $selected)
            ->whereHas('shipment_driver', fn(Builder $query) => $query->where('driver_id', '=', $driver_id))
            ->whereHas('order', fn(Builder $query) => $query->where('orders.order_id', '=', $order_id))
            ->findFirst(empty($values) ? ['*'] : array_merge(['order_in_process_road_id', 'shipment_driver_id'], $values));
    }

    /**
     * @inheritDoc
     */
    public function responseInStartSelectedRoute($order_id, $selected_route_id)
    {
        $shipment_driver = $this->processRoadContract->find($selected_route_id, ['order_in_process_road_id', 'shipment_driver_id', 'selected']);

        $has_selected = $this->processRoadContract
            ->where('order_in_process_road_id', '!=', $selected_route_id)
            ->where('shipment_driver_id', '=', $shipment_driver->shipment_driver_id)
            ->where('selected', '=', 1)
            ->findAll();

        if ($has_selected->count() >= 1) {
            foreach ($has_selected as $selected) {
                $this->processRoadContract->update($selected->order_in_process_road_id, ['selected' => 0]);
            }
        }

        $road = $this->processRoadContract->update($selected_route_id, ['selected' => 1]) ?: null;
        $order = $this->orderContract->find($order_id, ['order_id', 'address_to', 'to_coordinates']);

        return compact('road', 'order');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function orderEnd($order_id, $hash, $cords): ?Collection
    {
        $this->orderService->setStages('end', $cords, $order_id, 'ended');

        $_payload = $this->getOrderEndPayload($hash) ?: throw new Lexcept('Error Order is not data, fake order hash', 423);
        $calc_price = $this->orderEndService->orderEndreCalculate($_payload);
        $slip = $this->orderEndService->slipMaster((int)$order_id);
        $driver_price = $this->paymentService->driverPercent($order_id, $_payload->driver->driver_id, $calc_price->gets('price.price'),
            $_payload->order->payment_type_id);

        $this->orderEndService->driverOrderEndStatuses($order_id);
        $this->ratingService->setDriverRating($_payload->driver->driver_id, $order_id, $_payload->estimated_rating->added_patterns['ids']);

        $cords = [
            'address_from' => $_payload->order->address_from,
            'address_to' => $calc_price->get('end_address', $_payload->order->address_to),
            'from_cord' => $_payload->order->from_coordinates,
            'to_cord' => $cords
        ];

        return $calc_price
            ->merge($cords)
            ->merge($slip)
            ->merge(['balance' => $driver_price['balance']])
            ->merge(['driver_price' => $driver_price['price']])
            ->except('end_address');
    }

    /**
     * @return Collection
     */
    public function getTypes(): Collection
    {
        return $this->driverTypeContract->with('subtypes')->findAll();
    }

    /**
     * @return Collection
     */
    public function getTypesWithOptions(): Collection
    {
        $types = $this->driverTypeContract
            ->with([
                'franchise_options' => fn($query) => $query
                    ->select(['name', 'description', 'valued', 'driver_type_id', 'driver_type_optionals.driver_type_optional_id'])
            ])
            ->findAll(['driver_type_id', 'type', 'name', 'description', 'image']);

        $options = $this->driverTypeOptionalContract->findAll(['driver_type_optional_id', 'name', 'description', 'valued']);

        return $this->parseResult($instance, ['types', 'options'], [$types, $options]);
    }

    /**
     * @param $request
     * @return object|null
     */
    public function updateFranchiseOptionals($request): ?object
    {
        $type = $this->driverTypeContract->findOrFail($request->driver_type_id, ['driver_type_id', 'worked']);
        $options = [];

        foreach ($request->options as $iValue) {
            $options[$iValue] = ['franchise_id' => FRANCHISE_ID];
        }

        $type_option = $this->driverTypeOptionalContract
            ->where('valued', '=', true)
            ->findFirst(['driver_type_optional_id']);

        $options[$type_option->driver_type_optional_id] = ['percent_value' => $request->options_value, 'franchise_id' => FRANCHISE_ID];

        $update = $this->driverTypeContract->store($request->driver_type_id, ['franchise_options' => $options], true);

        if (!$update) {
            return null;
        }

        $driver_type = $this->driverTypeContract
            ->with([
                'franchise_options' => fn($query) => $query->select([
                    'driver_type_option.driver_type_optional_id',
                    'name',
                    'description',
                    'valued',
                    'driver_type_option_id',
                    'driver_type_id',
                    'percent_value',
                    'franchise_id'
                ])
            ])
            ->find($request->driver_type_id, ['driver_type_id', 'type', 'name', 'image', 'worked']);

        $driver_type->franchise_options->map(fn($item) => $item->driver_type_id = $type->driver_type_id);

        return $driver_type->franchise_options;
    }

    /**
     * @param  int  $driver_id
     * @return Collection
     */
    public function getDaysOrders(int $driver_id): Collection
    {
        $driver = $this->driverContract
            ->with(['orders' => fn(HasMany $query) => $query->whereDate('created_at', '=', Carbon::today()->format('Y-m-d H:i:s'))])
            ->whereHas(
                'waybills',
                fn(Builder $query) => $query
                    ->where('start_time', '<', now()->format('Y-m-d H:i:s'))
                    ->where('end_time', '>', now()->format('Y-m-d H:i:s'))
            )
            ->find($driver_id, ['driver_id', 'mean_assessment', 'rating']);

        if (!$driver) {
            $driver = $this->driverContract->find($driver_id, ['driver_id', 'mean_assessment', 'rating']);
            return $this->parseResult($instance, ['price', 'count', 'rating', 'assessment'], [0, 0, $driver->rating, $driver->mean_assessment]);
        }

        $price = (float)0;
        $price = $driver->orders->map(fn($item) => $item->cost + $price)->sum();

        return $this->parseResult(
            $instance,
            ['price', 'count', 'rating', 'assessment'],
            [round($price, 2, PHP_ROUND_HALF_EVEN), $driver->orders->count(), $driver->rating, $driver->mean_assessment]
        );
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     * @throws Exception
     */
    public function acceptCommonOrder(int $driver_id, int $order_id, string $hash, bool $accept): bool|array
    {
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'lat', 'lut']);
        $order = $this->orderContract->find($order_id, ['order_id', 'from_coordinates', 'address_from', 'from_coordinates']);

        if (!$driver || !$order) {
            return false;
        }

        if (!$accept) {
            $this->shippedContract
                ->where('order_id', '=', $order_id)
                ->where('driver_id', '=', $driver_id)
                ->where('status_id', '=', OrderShippedStatus::PRE_PENDING)
                ->updateSet(['status_id' => OrderShippedStatus::PRE_REJECTED, 'current' => false]);

            return ['order_id' => $order_id];
        }

        $preorder = $this->orderService->commonOrderAcceptStatuses($order_id, $driver_id);

        $from_cords = ['lat' => $driver->lat, 'lut' => $driver->lut];
        $to_cord = ['lat' => $order['from_coordinates']['lat'], 'lut' => $order['from_coordinates']['lut']];
        $on_way_hash = get_token();

        $shipped = $this->shippedContract->where('accept_hash', '=', $hash)->findFirst(['order_shipped_driver_id', 'order_id']);

        if (!$shipped) {
            throw new Lexcept('Hash invalid', 422);
        }

        $this->shippedContract
            ->where('accept_hash', '=', $hash)
            ->updateSet(['on_way_hash' => $on_way_hash, 'status_id' => ConstShippedStatus::pre_accepted()->getValue()]);

        $cord = $this->driverContract->getCordArray($driver_id);
        $this->orderService->setStages('accept', $cord, $order_id, 'accepted');

        $routes = [];
        $started = '';

        if ($preorder) {
            $started = $this->preOrderDriverAccept($order_id, $driver_id);
        } else {
            $this->orderContract->update($shipped->order_id, ['status_id' => OrderStatus::getStatusId(OrderStatus::ORDER_IN_PROCESS)]);
            $distance = $this->geoService->roadCalculation($from_cords, $to_cord, null, false);
            $routes = $this->orderService->createOrderProcessRoutes($shipped->order_shipped_driver_id, $distance, true);
        }

        return [
            'routes' => $routes,
            'order_id' => $order_id,
            'on_way_hash' => $on_way_hash,
            'address_from' => $order->address_from,
            'from_coordinates' => $order->from_coordinates,
            'delivery_time' => $started,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getProfileInitialData($driver_id): array
    {
        $driver = $this->driverContract
            ->with(['cash' => fn(HasOne $query) => $query->select(['driver_wallet_id', 'driver_id', 'balance'])])
            ->find($driver_id, ['driver_id', 'selected_class', 'selected_option']);

        [$distance, $balance] = $this->orderService->getWaybillDistanceWithPrice($driver_id);
        $classes = $this->getSelectedClassesOrderCount($driver_id, $driver->selected_class['ids']);
        $options = $this->getSelectedOptionsOrderCount($driver_id, $driver->selected_option['ids'] ?? null);

        return compact('classes', 'options', 'distance', 'balance');
    }

    /**
     * @param $driver_id
     * @param $classes
     * @return array
     */
    public function getSelectedClassesOrderCount($driver_id, $classes): array
    {
        $count_order = [];

        foreach ($classes as $class) {
            $count_order[$class] = $this->orderContract
                ->whereHas('completed', fn($query) => $query->where('driver_id', '=', $driver_id))
                ->where('car_class_id', '=', $class)
                ->count();
        }

        return $count_order;
    }

    /**
     * @inheritDoc
     */
    public function getSelectedOptionsOrderCount($driver_id, array $options = null): array
    {
        $count_order = [];

        if (!$options) {
            return $count_order;
        }

        foreach ($options as $option) {
            $count_order[$option] = $this->orderContract
                ->whereHas('completed', fn(Builder $query) => $query->where('driver_id', '=', $driver_id))
                ->whereJsonContains('car_option->ids', $option)
                ->count();
        }

        return $count_order;
    }

    /**
     * @throws Lexcept
     */
    public function changeOnlineStatus(int $driver_id, bool $status_online): bool
    {
        if (!$this->driverContract->update($driver_id, ['online' => $status_online])) {
            throw new Lexcept('Error when updated online status', 500);
        }

        return true;
    }

    /**
     * @param $driver_id
     * @return array|null
     * @throws ReflectionException
     * @throws UnauthorizedException
     */
    public function getRealState($driver_id): ?array
    {
        $driver = $this->driverContract
            ->with([
                'current_order' => fn($query) => $query->select([
                    'orders.order_id',
                    'orders.status_id'
                ]),
                'last_completed_order' => fn($query) => $query->select([
                    'completed_orders.completed_order_id',
                    'completed_orders.order_id',
                    'completed_orders.driver_id'
                ]),
            ])
            ->find($driver_id, ['driver_id', 'current_status_id', 'is_ready', 'lat', 'lut']);

        if (!$driver) {
            throw new UnauthorizedException('Unauthorized By state', 401);
        }

        return GetDriverState::complex($driver);
    }

    /**
     * @param $driver_id
     * @param $image
     * @return void
     */
    public function profileImageUpload($driver_id, $image): void
    {
        $path = storage_path('public'.DS.'drivers'.DS.'info'.DS);
        $driver = $this->driverContract->with(['driver_info' => fn(BelongsTo $query) => $query->select('*')])->find($driver_id);
        $this->deleteOldFile($driver->driver_info->photo);

        $new_image = $this->fileUpload($image, $path);
        $this->driverInfoContract->where('driver_id', '=', $driver_id)->updateSet(['photo' => "/storage/drivers/info/$new_image"]);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function toggleCarClass(int $driver_id, int $classes): void
    {
        $this->toggleClassOptions($driver_id, $classes, 'class');
    }

    /**
     * @param $driver_id
     * @return bool
     */
    public function driverHasAcceptOrder($driver_id): bool
    {
        return $this->driverContract
            ->where($this->driverContract->getKeyName(), '=', $driver_id)
            ->whereHas('status', fn(Builder $query) => $query->where('status', '!=', DriverStatus::DRIVER_IS_FREE))
            ->exists();
    }

    /**
     * @param $driver_id
     * @return bool
     */
    public function driverIsRejectOrder($driver_id): bool
    {
        return $this->driverContract
            ->where($this->driverContract->getKeyName(), '=', $driver_id)
            ->whereHas('status', fn(Builder $current_status_query) => $current_status_query->where('status', '=', DriverStatus::DRIVER_IS_FREE))
            ->doesntHave('active_order_shipment')
            ->exists();
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function toggleCarOption(int $driver_id, int $options): void
    {
        $this->toggleClassOptions($driver_id, $options, 'option');
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'rating' => 'mixed'
    ])]
    public function driverRejectOrder(int $order_id, int $driver_id, int $option, string $text = null): array
    {
        $shipped = $this->shippedContract
            ->where('order_id', '=', $order_id)
            ->where('driver_id', '=', $driver_id)
            ->with(['estimated_rating' => fn($query) => $query->select(['driver_id', 'order_id', 'estimated_rating_id', 'remove_rating', 'remove_patterns'])])
            ->findFirst([$this->shippedContract->getKeyName(), 'driver_id', 'order_id', 'estimated_rating_id', 'accept_hash']);

        $this->estimatedRatingContract->where('order_id', '=', $order_id)->where('driver_id', '=', $driver_id)->updateSet(['outcome' => 'removed']);
        $this->shippedContract->update(
            $shipped->{$shipped->getKeyName()},
            ['current' => false, 'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_REJECTED)]
        );
        $this->orderContract->where('order_id', '=', $order_id)->updateSet(['status_id' => OrderStatus::getStatusId(OrderStatus::ORDER_PENDING)]);
        $this->driverContract->where('driver_id', '=', $driver_id)->updateSet(['current_status_id' => DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE)]);

        $ratings = array_merge($shipped->estimated_rating->remove_patterns['ids'], ['rating' => ConstRatingPattern::RESET_AFTER_ACCEPT()->getValue()]);
        $rating = $this->ratingService->setDriverRating($driver_id, $order_id, $ratings);
        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);
        $result = $this->orderService->repeatOrder($order_id);
        $this->driverLocked($driver_id);

        $payload = ['continue' => $result, 'message' => 'Cancel'];
        OrderReset::broadcast($client, $payload, trans('messages.order_search_car'));

        $this->feedbackContract->create([
            'order_id' => $order_id,
            'writable_id' => $driver_id,
            'writable_type' => $this->driverContract->getMap(),
            'readable_id' => $client->client_id,
            'readable_type' => $client->getMap(),
            'feedback_option_id' => $option,
            'text' => $text,
        ]);

        return ['rating' => $rating->get('rating')];
    }

    /**
     * @param  string  $search
     * @return Collection
     */
    public function searchDrivers(string $search): Collection
    {
        return $this->driverContract
            ->with(['current_waybill'])
            ->where('phone', 'LIKE', '%'.$search.'%')
            ->orWhereHas('driver_info', fn(Builder $query) => $query
                ->whereRaw("CONCAT(`surname`, ' ', `name`, ' ', `patronymic`) LIKE '%".$search."%'")
                ->orWhere('email', 'LIKE', '%'.$search.'%'))
            ->findAll(['driver_id', 'driver_info_id', 'phone']);
    }

    /**
     * @inheritdoc
     */
    public function getDebt($driver_id): mixed
    {
        return $this->driverContract
            ->with(['cash' => fn($query) => $query->select(['driver_wallet_id', 'driver_id', 'debt'])])
            ->find($driver_id, ['driver_id'])
            ->cash;
    }

    /**
     * @inheritdoc
     * @throws BindingResolutionException
     */
    public function getCommonOrders(int $driver_id): array
    {
        $driver = $this->driverContract
            ->with([
                'common_orders' => fn(HasManyJson $query) => $query
                    ->where('emergency', '=', false)
                    ->where('accept', '=', false)
                    ->where('manual', '=', false)
                    ->whereHas('order', fn(Builder $query) => $query->where('status_id', '=', OrderStatus::ORDER_PENDING))
                    ->where(fn($query) => $query
                        ->whereHas('shipped', fn(Builder $query) => $query->where('status_id', '=', OrderShippedStatus::PRE_PENDING))
                        ->orDoesntHave('shipped'))
                    ->with([
                        'preorder' => fn(BelongsTo $query) => $query
                            ->where('active', '=', true)
                            ->where('time', '>=', now(session('app_system.timezone')))
                            ->whereHas('order', fn(Builder $query) => $query->where('status_id', '=', OrderStatus::ORDER_PENDING))
                            ->where(fn($query) => $query
                                ->whereHas('shipped', fn(Builder $query) => $query->where('status_id', '=', OrderShippedStatus::PRE_PENDING))
                                ->orDoesntHave('shipped')
                            )
                            ->select(['order_id', 'time', 'preorder_id'])
                    ])
                    ->select(['driver', 'order_id', 'order_common_id']),
            ])
            ->find($driver_id, ['driver_id', 'car_id', 'phone']);

        if (!$driver) {
            return [];
        }

        return $this->prepareCommonResult($driver->common_orders, $driver->driver_id);
    }

    /**
     * @param $common_orders
     * @param $driver_id
     * @return array
     * @throws BindingResolutionException
     */
    protected function prepareCommonResult($common_orders, $driver_id): array
    {
        $orders = [];

        foreach ($common_orders as $common) {
            if ($common->preorder) {
                $orders[] = $this->getCommonPayload($common->preorder, $driver_id, true);
            } else {
                $orders[] = $this->getCommonPayload($common, $driver_id);
            }
        }

        return $orders;
    }

    /**
     * @param  int  $driver_id
     * @return array
     * @throws BindingResolutionException
     */
    public function getCommonArmorsOrders(int $driver_id): array
    {
        $driver = $this->driverContract
            ->with([
                'common_orders' => fn(HasManyJson $query) => $query
                    ->where('accept', '=', true)
                    ->where('manual', '=', false)
                    ->whereHas('shipped', fn(Builder $query) => $query
                        ->where('order_shipped_drivers.driver_id', '=', $driver_id)
                        ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                    )
                    ->with([
                        'preorder' => fn(BelongsTo $query) => $query
                            ->where('active', '=', true)
                            ->where('time', '>=', now(session('app_system.timezone')))
                            ->whereHas('order', fn(Builder $query) => $query->where('status_id', '=', OrderStatus::ORDER_PENDING))
                            ->where(fn($query) => $query
                                ->whereHas('shipped', fn(Builder $query) => $query
                                    ->where('driver_id', '=', $driver_id)
                                    ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                                )
                                ->orDoesntHave('shipped')
                            )
                            ->select(['order_id', 'time', 'preorder_id'])
                    ])
                    ->select(['driver', 'order_id', 'order_common_id']),
            ])
            ->find($driver_id, ['driver_id', 'car_id', 'phone']);

        if (!$driver) {
            return [];
        }

        return $this->prepareCommonResult($driver->common_orders, $driver->driver_id);
    }

    /**
     * @throws Exception
     */
    public function cancelCommonOrder(int $driver_id, int $order_id): bool
    {
        $preorder = $this->preorderContract->where('order_id', '=', $order_id)->findFirst(['preorder_id', 'order_id', 'distribution_start']);

        if (!$preorder) {
            throw new Lexcept('Undefined preorder', 500);
        }

        $this->driverContract->beginTransaction(function () use ($preorder, $order_id, $driver_id) {
            if (!$this->preorderContract->update($preorder->preorder_id, ['distribution_start' => null])) {
                return false;
            }

            if (!$this->commonContract->where('order_id', '=', $order_id)->where('accept', '=', true)->updateSet(['accept' => false, 'accepted' => null])) {
                return false;
            }

            if (!$this->shippedContract
                ->where('order_id', '=', $order_id)
                ->where('driver_id', '=', $driver_id)
                ->updateSet(['current' => false, 'status_id' => OrderShippedStatus::PRE_REJECTED])
            ) {
                return false;
            }
        });

        try {
            $this->autoDispatch($order_id);
        } catch (Exception $exception) {
            write($exception->getMessage());
        } finally {
            return true;
        }
    }

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  bool  $accept
     * @return void
     */
    public function questionPreorderAccept(int $driver_id, int $order_id, $started, bool $accept): void
    {
        if (!$accept) {
            $this->shippedContract
                ->where('driver_id', '=', $driver_id)
                ->where('order_id', '=', $order_id)
                ->updateSet(['status_id' => OrderShippedStatus::PRE_REJECTED, 'current' => false]);

            $this->preorderContract->where('order_id', '=', $order_id)->updateSet(['accept' => false]);

            return;
        }

        $this->preorderContract->where('order_id', '=', $order_id)->updateSet(['distribution_start' => $started]);
    }

    /**
     * @param $driver_id
     * @param $driver_info_id
     * @param $data
     * @return bool
     */
    public function updateDriverInfoFields($driver_id, $driver_info_id, $data): bool
    {
        $driverUpdated = $this->driverContract
            ->where('driver_id', '=', $driver_id)
            ->updateSet($data['driver']);

        $driverInfoUpdated = $this->driverInfoContract
            ->where('driver_info_id', '=', $driver_info_id)
            ->updateSet($data['driver_info']);

        return $driverUpdated && $driverInfoUpdated;
    }

    /**
     * @param $driver_id
     * @param $new_password
     * @return bool
     */
    public function updateDriverPassword($driver_id, $new_password): bool
    {
        return $this->driverContract->where('driver_id', '=', $driver_id)
            ->updateSet($new_password);
    }


    /**
     * @param $driver_id
     * @param $car_id
     * @return bool
     * @throws Exception
     */
    public function removeDriverOnCar($driver_id, $car_id): bool
    {
        $this->transactionContract->beginTransaction();

        if (!$this->driverContract->where('driver_id', '=', $driver_id)
            ->updateSet(['car_id' => null])) {
            $this->transactionContract->rollBack();

            return false;
        }

        if (!$this->carContract->where('car_id', '=', $car_id)
            ->where('current_driver_id', '=', $driver_id)
            ->updateSet(['current_driver_id' => null])) {
            $this->transactionContract->rollBack();

            return false;
        }

        if (!$this->driverContractContract->where('driver_id', '=', $driver_id)
            ->updateSet(['active' => 0, 'signed' => 0])) {
            $this->transactionContract->rollBack();

            return false;
        }
        $this->transactionContract->commit();

        return true;
    }
}
