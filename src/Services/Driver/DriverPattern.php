<?php

declare(strict_types=1);

namespace Src\Services\Driver;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use Src\Broadcasting\Broadcast\Client\DriverOnWayOrderEvent;
use Src\Broadcasting\Broadcast\Client\ListenRadiusTaxiEvent;
use Src\Broadcasting\Broadcast\Driver\CommonOrderEvent;
use Src\Broadcasting\Broadcast\Driver\LockedInfo;
use Src\Core\Complex\PreorderStartedTime;
use Src\Core\Enums\ConstQueue;
use Src\Exceptions\Lexcept;
use Src\Http\Resources\Driver\DriverMapViewResource;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Jobs\OrderCommons\CancelPriceCalcQue;
use Src\Jobs\OrderProcessing\CommonListTikTak;
use Src\Jobs\OrderProcessing\DriverPreorderStart;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Repositories\ClientDriverView\ClientDriverViewContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverLock\DriverLockContract;
use Src\Repositories\EstimatedRating\EstimatedRatingContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderInProcessRoad\OrderInProcessRoadContract;
use Src\Repositories\OrderOnWayRoad\OrderOnWayRoadContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\RatingPointService\RatingPointServiceContract;
use Src\Services\Region\RegionService;
use Src\Services\Tariff\TariffService;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

use function in_array;

class DriverPattern
{
    /**
     * @param  DriverContract  $driverContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  OrderInProcessRoadContract  $processRoadContract
     * @param  OrderProcessContract  $orderProcessContract
     * @param  ClientServiceContract  $clientService
     * @param  OrderOnWayRoadContract  $orderOnWayRoadContract
     * @param  OrderServiceContract  $orderService
     * @param  OrderContract  $orderContract
     * @param  ClientDriverViewContract  $driverViewContract
     * @param  GeocodeServiceContract  $geoService
     * @param  PreorderContract  $preorderContract
     * @param  DriverLockContract  $lockContract
     * @param  RatingPointServiceContract  $ratingService
     * @param  EstimatedRatingContract  $estimatedRatingContract
     * @param  TariffService  $tariffService
     * @param  RegionService  $regionService
     * @param  DriverServiceContract  $driverService
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected OrderShippedDriverContract $shippedContract,
        protected OrderInProcessRoadContract $processRoadContract,
        protected OrderProcessContract $orderProcessContract,
        protected ClientServiceContract $clientService,
        protected OrderOnWayRoadContract $orderOnWayRoadContract,
        protected OrderServiceContract $orderService,
        protected OrderContract $orderContract,
        protected ClientDriverViewContract $driverViewContract,
        protected GeocodeServiceContract $geoService,
        protected PreorderContract $preorderContract,
        protected DriverLockContract $lockContract,
        protected RatingPointServiceContract $ratingService,
        protected EstimatedRatingContract $estimatedRatingContract,
        protected TariffService $tariffService,
        protected RegionService $regionService,
        protected DriverServiceContract $driverService,
        protected OrderCommonContract $commonContract
    ) {
    }

    /**
     * @param $hash
     * @param $route_id
     * @return Collection|null
     */
    public function inStartOrderWithRoute($hash, $route_id): ?Collection
    {
        $route = $this->processRoadContract
            ->whereHas('shipment_driver', fn(Builder $q_shipment) => $q_shipment->where('in_order_hash', '=', $hash))
            ->find($route_id, ['order_in_process_road_id', 'distance', 'duration', 'selected']);

        if (!$route) {
            return null;
        }

        if (!$route->selected) {
            $this->processRoadContract
                ->whereHas('shipment_driver', fn(Builder $q_shipment) => $q_shipment->where('in_order_hash', '=', $hash))
                ->updateSet(['selected' => 1]);
        }

        return parse_result($instance, ['route'], [$route]);
    }

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  string  $hash
     * @param  int  $selected_route_id
     * @return array|null
     * @throws JsonException
     * @throws Lexcept
     * @throws Exception
     */
    public function regularOnWay(int $driver_id, int $order_id, string $hash, int $selected_route_id): ?array
    {
        $shipped = $this->shippedContract
            ->where('on_way_hash', '=', $hash)
            ->findFirst(['order_shipped_driver_id', 'order_id', 'on_way_hash']);

        if (!$shipped) {
            throw new Lexcept('Error hash', 422);
        }

        $this->driverContract->beginTransaction();

        $new_hash = get_token();

        if (!$this->shippedContract->update($shipped->order_shipped_driver_id, ['in_place_hash' => $new_hash])) {
            $this->driverContract->rollBack();
            return null;
        }

        if (!$this->driverContract->update($driver_id, ['current_status_id' => DriverStatus::DRIVER_ON_WAY])) {
            $this->driverContract->rollBack();
            return null;
        }

        if (!$this->orderOnWayRoadContract->update($selected_route_id, ['selected' => true])) {
            $this->driverContract->rollBack();
            return null;
        }

        $this->driverContract->commit();

        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);
        $cord = $this->driverContract->getCordArray($driver_id);
        $this->taxiMapDistribution($client, $driver_id, $order_id, $selected_route_id, $cord, $hash);

        return ['hash' => $new_hash, 'order_id' => $order_id];
    }

    /**
     * @param $client
     * @param $driver_id
     * @param $order_id
     * @param $selected_route_id
     * @param $cord
     * @param $hash
     * @throws JsonException
     */
    public function taxiMapDistribution($client, $driver_id, $order_id, $selected_route_id, $cord, $hash): void
    {
        $driver = $this->driverService->getDriverUpdatedDriverData($driver_id);

        $this->driverAddClientRoadView($driver, $client->client_id, $order_id);

        $this->orderService->setStages('on_way', $cord, $order_id, 'on_wayed');

        $matrix = $this->orderOnWayRoadContract->find($selected_route_id, ['order_on_way_road_id', 'distance', 'duration']);

        DriverOnWayOrderEvent::broadcast($client, new DriverMapViewResource($driver), $matrix->duration, trans('messages.driver_go_to_you'));

        CancelPriceCalcQue::dispatch($hash, $order_id, now(), $cord, $matrix->duration, $matrix->distance)->onQueue(ConstQueue::BASE()->getValue());
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function driverAddClientRoadView($driver, $client_id, $order_id): bool
    {
        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);

        $this->driverViewContract
            ->where('clientable_id', '=', $client_id)
            ->where('clientable_type', '=', $client->getMap())
            ->updateSet(['driver' => decode(['ids' => [$driver['driver_id']]])]);

        $this->deleteDriverClientsView($client, $driver['driver_id']);

        ListenRadiusTaxiEvent::broadcast($client, DriverMapViewResource::collection([$driver]), 'deleted');

        return true;
    }

    /**
     * @param $client
     * @param $driver_id
     * @param  int|null  $road_id
     * @throws JsonException
     */
    public function deleteDriverClientsView($client, $driver_id, int $road_id = null): void
    {
        $driver_viewed_client = $this->driverContract
            ->with([
                'client_driver_view' => fn($query) => $query
                    ->where('clientable_id', '!=', $client->client_id)
                    ->where('clientable_type', '!=', $client->getMap())
                    ->with(['client'])
                    ->select('client_driver_view_id', 'clientable_id', 'clientable_type', 'driver')
            ])
            ->find($driver_id, [$this->driverContract->getKeyName(), 'lat', 'lut']);

        foreach ($driver_viewed_client->client_driver_view as $client_view) {
            $drivers = $client_view->driver['ids'];
            delete_element_in($driver_id, $drivers);

            $this->driverViewContract->update($client_view->{$client_view->getKeyName()}, ['driver' => decode(['ids' => array_values($drivers)])]);
            ListenRadiusTaxiEvent::broadcast(
                $client_view->client,
                DriverMapViewResource::collection([$this->driverService->getDriverUpdatedDriverData($driver_id)]),
                'deleted'
            );
        }
    }

    /**
     * @param $hash
     * @param $from_cord
     * @return array|null
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    public function driverAcceptedOrder($hash, $from_cord): ?array
    {
        $this->orderContract->beginTransaction();
        $this->orderContract->forgetCache();

        $shipped = $this->shippedContract
            ->where('accept_hash', $hash)
            ->findFirst(['order_shipped_driver_id', 'driver_id', 'order_id', 'accept_hash']);

        $generate_on_way_hash = get_token();

        $hash_status_update = $this->shippedContract
            ->where('accept_hash', '=', $hash)
            ->updateSet([
                'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_ACCEPTED),
                'on_way_hash' => $generate_on_way_hash
            ]);

        if (!$hash_status_update) {
            $this->orderContract->rollBack();
            return null;
        }

        $order_status_update = $this->orderContract->update($shipped->order_id,
            ['status_id' => OrderStatus::getStatusId(OrderStatus::ORDER_IN_PROCESS)]);

        if (!$order_status_update) {
            $this->orderContract->rollBack();
            return null;
        }

        $driver_status_update = $this->driverContract->update($shipped->driver_id,
            ['current_status_id' => DriverStatus::getStatusId(DriverStatus::DRIVER_ON_ACCEPT)]);

        if (!$driver_status_update) {
            $this->orderContract->rollBack();
            return null;
        }

        $this->orderContract->commit();

        $order = $this->shippedContract
            ->where('accept_hash', '=', $hash)
            ->with([
                'order:order_id,client_id,from_coordinates',
                'driver:driver_id,lat,lut',
                'order.passenger'
            ])
            ->findFirst([
                'order_shipped_driver_id',
                'driver_id',
                'order_id',
                'accept_hash',
            ]);

        $routes = $this->geoService->roadCalculation($from_cord, $order->order->from_coordinates, null, false);
        $shipment_driver_id = $shipped['order_shipped_driver_id'];
        $route_created = $this->orderService->createOrderProcessRoutes($shipment_driver_id, $routes, true);

        foreach ($route_created as $key => $value) {
            $routes['routes'][$key] = [
                'route' => $value['route'],
                'duration' => $value['duration'],
                'distance' => $value['distance'],
                'order_on_way_road_id' => $value['order_on_way_road_id']
            ];
        }

        $routes['generate_on_way_hash'] = $generate_on_way_hash;
        $routes['order_id'] = $order->order->order_id;

        return $routes;
    }

    /**
     * @param $hash
     * @param $driver_id
     * @return Collection
     */
    public function inStartOrderWithoutData($hash, $driver_id): Collection
    {
        $shipped = $this->shippedContract
            ->where('in_order_hash', '=', $hash)
            ->where('driver_id', '=', $driver_id)
            ->with([
                'driver' => fn(BelongsTo $q_driver) => $q_driver
                    ->where('current_status_id', '=', DriverStatus::getStatusId(DriverStatus::DRIVER_IN_PLACE))
                    ->select(['driver_id']),
                'in_process_roads' => fn(HasMany $_q_in_process) => $_q_in_process
                    ->where('selected', '=', 1)
                    ->select(['order_in_process_road_id', 'shipment_driver_id', 'distance', 'duration', 'selected']),
                'initial_order' => fn(HasOneDeep $q_initial) => $q_initial
                    ->select([
                        'order_initial_data_id',
                        'orderable_id',
                        'orderable_type',
                        'price',
                        'initial',
                        'order_initial_data.order_id'
                    ])
            ])
            ->findFirst(['order_shipped_driver_id', 'driver_id', 'in_order_hash', 'order_id']);

        if ($shipped && $shipped->in_process_roads->count() < 1) {
            $this->startWithoutDataShippedRoad($shipped);
        }

        $routes = $shipped->in_process_roads->count() > 0 ? $shipped->in_process_roads->first() : $shipped->in_process_roads;

        return parse_result($routes, ['coin', 'initial'], [$shipped->initial_order->price, $shipped->initial_order->initial]);
    }

    /**
     * @param $shipped
     */
    public function startWithoutDataShippedRoad($shipped): void
    {
        $process_road = $this->processRoadContract
            ->where('shipment_driver_id', '=', $shipped->{$shipped->getKeyName()})
            ->findAll('order_in_process_road_id', 'shipment_driver_id', 'distance', 'duration', 'selected');

        if ($process_road->count() >= 1) {
            $routes = $process_road;
            $distances = [];
            $durations = [];

            foreach ($routes as $route) {
                $distances[$route->order_in_process_road_id] = $route->distance;
                $durations[$route->order_in_process_road_id] = $route->duration;
            }

            $min_distance = array_keys($distances, min($distances));
            $min_duration = array_keys($durations, min($durations));

            if (count($min_distance) > 1 && count($min_duration) > 1) {
                $routes = $process_road->first();
                $this->processRoadContract->update($routes->{$routes->getKeyName()}, ['selected' => 1]);
            } elseif (1 === count($min_duration)) {
                $routes = $process_road->where('order_in_process_road_id', array_first($min_duration))->first();
                $this->processRoadContract->update($routes->{$routes->getKeyName()}, ['selected' => 1]);
            } elseif (1 === count($min_distance) && 1 !== count($min_duration)) {
                $routes = $process_road->where('order_in_process_road_id', array_first($min_distance))->first();
                $this->processRoadContract->update($routes->{$routes->getKeyName()}, ['selected' => 1]);
            }
        }
    }

    /**
     * @param  string  $hash
     * @return object|null
     */
    public function getOrderEndPayload(string $hash): ?object
    {
        return $this->shippedContract
            ->where('end_hash', '=', $hash)
            ->with([
                'order' => fn(BelongsTo $q_order) => $q_order
                    ->select([
                        'orders.order_id',
                        'status_id',
                        'client_id',
                        'from_coordinates',
                        'to_coordinates',
                        'address_from',
                        'address_to',
                        'order_type_id',
                        'payment_type_id'
                    ]),
                'in_process_road' => fn(HasOne $q_process_roads) => $q_process_roads
                    ->select([
                        'order_in_process_road_id',
                        'shipment_driver_id',
                        'real_road',
                        'distance',
                        'duration'
                    ]),
                'driver' => fn(BelongsTo $q_driver) => $q_driver
                    ->select(['driver_id', 'car_id']),
                'estimated_rating' => fn(BelongsTo $query) => $query
                    ->select(['estimated_rating_id', 'driver_id', 'order_id', 'added_patterns']),
                'initial_order_tariff' => fn(HasOneDeep $query) => $query
                    ->select(['tariff_id', 'minimal_price']),
                'second_order_tariff' => fn(HasOneDeep $query) => $query
                    ->select(['tariff_id', 'minimal_price']),
                'order_stages' => fn($query) => $query
                    ->select([
                        'order_stages_cord.order_stage_cord_id',
                        'order_stages_cord.order_id',
                        'start',
                        'end'
                    ]),
                'initial_order' => fn(HasOneDeep $q_initial_order) => $q_initial_order
                    ->select([
                        'order_initial_data_id',
                        'order_initial_data.order_id',
                        'order_initial_data.price',
                        'price',
                        'sitting_fee',
                        'currency',
                        'initial_tariff_id',
                        'second_tariff_id',
                        'behind',
                        'region_id',
                        'city_id',
                    ]),
                'process' => fn(HasOne $query) => $query
                    ->select([
                        'order_shipped_id',
                        'price',
                        'total_price',
                        'calculate_price',
                        'pause_price',
                        'options_price',
                        'sitting_price',
                        'cancel_price',
                        'waiting_price',
                        'order_process_id',
                        'travel_time',
                        'waiting_time',
                        'pause_time',
                        'distance_traveled',
                    ])
            ])
            ->findFirst([
                'order_shipped_driver_id',
                'estimated_rating_id',
                'driver_id',
                'order_shipped_drivers.order_id',
                'status_id',
                'end_hash',
                'order_id'
            ]);
    }

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @return false|Carbon
     * @throws Exception
     */
    public function preOrderDriverAccept(int $order_id, int $driver_id): bool|Carbon
    {
        $p_order = $this->preorderContract
            ->where('order_id', '=', $order_id)
            ->with([
                'location_zone' => fn($query) => $query->select('zone_string', 'timezone_id'),
                'customer_zone' => fn($query) => $query->select('zone_string', 'timezone_id'),
                'common' => fn($query) => $query->select('order_id', 'driver'),
            ])
            ->findFirst(['time', 'order_id']);

        if (!$p_order) {
            return false;
        }

        if ($p_order['common']['driver']['ids']) {
            $drivers = $this->driverContract
                ->where('driver_id', '!=', $driver_id)
                ->whereIn('driver_id', $p_order['common']['driver']['ids'])
                ->findAll(['driver_id', 'car_id', 'current_franchise_id', 'phone']);

            foreach ($drivers as $driver) {
                CommonOrderEvent::broadcast($driver, new PassOrderResource(['order_id' => $order_id]), 'delete');
            }
        }

        $started_time = PreorderStartedTime
            ::complex($order_id, $driver_id, $p_order->time, $p_order->location_zone->zone_string, $p_order->customer_zone->zone_string);

        if (!$this->preorderContract->where('order_id', '=', $order_id)->where('active', '=', true)->updateSet(['distribution_start' => $started_time])) {
            return false;
        }

        DriverPreorderStart
            ::dispatch($driver_id, $order_id, $started_time)
            ->onQueue(ConstQueue::LONG()->getValue())
            ->delay($started_time);

        return $started_time;
    }

    /**
     * @param $driver_id
     */
    public function driverLocked($driver_id): void
    {
        $locked = $this->lockContract->where('driver_id', '=', $driver_id)->findFirst([
            'driver_id',
            'driver_lock_id',
            'locked',
            'end'
        ]);

        if (!$locked || $locked->locked) {
            return;
        }

        $minute = 30;

        $shipped = $this->shippedContract
            ->where('driver_id', '=', $driver_id)
            ->where('status_id', '=', OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_REJECTED))
            ->where('created_at', '>', $locked->end ? $locked->end->subHours(3) : now()->subHours(3))
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll(['order_shipped_driver_id', 'driver_id', 'order_id', 'status_id']);

        if (4 > $shipped->count()) {
            return;
        }

        $this->lockContract->updateOrCreate(
            ['driver_id', '=', $driver_id],
            [
                'driver_id' => $driver_id,
                'locked' => true,
                'lock_count' => DB::raw('lock_count+'. 1),
                'start' => now(),
                'end' => now()->addMinutes($minute)
            ]
        );

        $driver = $this->driverContract->find($driver_id, ['driver_id', 'car_id', 'phone', 'current_franchise_id']);
        LockedInfo::broadcast($driver, [
            'message' => trans('messages.driver_locked_up', ['minute' => $minute]),
            'locked' => true,
            'time' => $minute
        ]);
    }

    /**
     * @param $common
     * @param $driver_id
     * @param  bool  $preorder
     * @return array
     */
    #[ArrayShape([
        'order_id' => 'mixed',
        'cash' => 'bool',
        'company_name' => 'mixed',
        'rating_rejected' => 'mixed',
        'rating_accepted' => 'mixed',
        'address_from' => 'mixed',
        'cord_from' => 'mixed'
    ])] public function getCommonPayload($common, $driver_id, bool $preorder = false): array
    {
        $order = $this->orderContract
            ->with([
                'company' => fn($query) => $query->select(['companies.company_id', 'name']),
                'estimated_rating' => fn($query) => $query
                    ->where('driver_id', '=', $driver_id)
                    ->select(['remove_rating', 'added_rating', 'driver_id', 'order_id', 'estimated_rating_id'])
            ])
            ->find($common->order_id, ['order_id', 'address_from', 'from_coordinates']);

        if (!$order) {
            return [];
        }

        return [
            'order_id' => $order->order_id,
            'cash' => !$order->company,
            'company_name' => $order->company->company_name ?? '',
            'rating_rejected' => $order->estimated_rating->remove_rating ?? '',
            'rating_accepted' => $order->estimated_rating->added_rating ?? '',
            'address_from' => $order->address_from,
            'cord_from' => $order->from_coordinates,
            'delivery_time' => $preorder ? $common->time : 0,
        ];
    }

    /**
     * @param $hash
     * @return array
     */
    public function createOrderInProcessHashes($hash): array
    {
        $pause_hash = get_token();
        $end_hash = get_token();

        $this->shippedContract
            ->where('in_order_hash', $hash)
            ->updateSet(compact('pause_hash', 'end_hash'));

        return compact('pause_hash', 'end_hash');
    }

    /**
     * @param $driver_id
     * @return mixed
     */
    public function getDriverUpdatedData($driver_id): mixed
    {
        $this->driverContract = app(DriverContract::class);

        return $this->driverContract
            ->with([
                'car' => fn(BelongsTo $car_query) => $car_query->select([
                    'car_id',
                    'current_driver_id',
                    'class',
                    'mark',
                    'model',
                    'color',
                    'state_license_plate'
                ]),
                'driver_info' => fn(BelongsTo $info_query) => $info_query->select([
                    'driver_info_id',
                    'name',
                    'surname',
                    'photo'
                ])
            ])
            ->find($driver_id, [
                $this->driverContract->getKeyName(),
                'car_id',
                'driver_info_id',
                'current_franchise_id',
                'lat',
                'lut',
                'phone'
            ]);
    }

    /**
     * @param $order_id
     * @param $hash
     * @param $driver_id
     * @param $start_cords
     * @param $to_cord
     * @return Collection|null
     * @throws JsonException
     * @throws Lexcept
     */
    public function inStartOrderWithCords($order_id, $hash, $driver_id, $start_cords, $to_cord): ?Collection
    {
        $price = $this->priceReCalculate($hash, $order_id, $driver_id, $start_cords, $to_cord);

        $initial = $this->shippedContract
            ->where('in_order_hash', '=', $hash)
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['order_shipped_driver_id', 'driver_id', 'in_order_hash', 'order_id']);

        $road = $this->geoService->roadCalculation($initial->order->from_coordinates, $to_cord);

        $results = $this->processRoadContract->updateOrCreate(
            ['shipment_driver_id', '=', $initial->order_shipped_driver_id],
            [
                'route' => decode($road['points']),
                'distance' => $road['distance'],
                'duration' => $road['duration'],
                'selected' => 1
            ]
        );

        return parse_result($price, ['order_in_process_road_id', 'route'], [$results->first()->order_in_process_road_id, $road]);
    }

    /**
     * @param $hash
     * @param $order_id
     * @param $driver_id
     * @param $start_cord
     * @param $to_cord
     * @return array
     * @throws JsonException
     * @throws Lexcept
     */
    public function priceReCalculate($hash, $order_id, $driver_id, $start_cord, $to_cord): array
    {
        $geocode = $this->geoService->getRightGeocode($start_cord, false, true);
        $this->regionService->detectCountryRegions($geocode, null, true);

        $price = $this->tariffService->driverOnWayPriceCalculate($hash, $driver_id, $start_cord, $to_cord);

        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);
        $this->orderService->setInitialOrderData($price['tariff'], $price['price'], $client, array_values($start_cord), $order_id);

        $to = $this->geoService->fullAddressGeocode((float)$to_cord['lat'], (float)$to_cord['lut']);
        $this->orderContract->where('order_id', '=', $order_id)->updateSet([
            'address_to' => $to['full_address'],
            'to_coordinates' => decode($to_cord)
        ]);

        return $price['price'];
    }

    /**
     * @param  int  $driver_id
     * @param  int  $value
     * @param  string  $type
     * @throws JsonException
     */
    public function toggleClassOptions(int $driver_id, int $value, string $type): void
    {
        $driver = $this->driverContract->find($driver_id, ['driver_id', "selected_$type"]);
        $ids = 'class' === $type ? $driver->selected_class['ids'] ?? [] : $driver->selected_option['ids'] ?? [];

        if (empty($ids)) {
            $this->driverContract->update($driver_id, ["selected_$type" => decode(['ids' => [$value]])]);
            return;
        }

        if (in_array($value, $ids, true)) {
            $key = array_search($value, $ids, true);
            unset($ids[$key]);
            $this->driverContract->update($driver_id, ["selected_$type" => decode(['ids' => array_values($ids)])]);
            return;
        }

        $new_selected = array_unique(array_merge($ids, [$value]));
        $this->driverContract->update($driver_id, ["selected_$type" => decode(['ids' => array_values($new_selected)])]);
    }

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  string  $accept_hash
     * @param  bool  $accept
     * @return bool|array
     * @throws JsonException
     * @throws Exception
     */
    public function declineCommonOrder(int $driver_id, int $order_id, string $accept_hash, bool $accept): bool|array
    {
        $shipped = $this->shippedContract->where('on_way_hash', '=', $accept_hash)->findFirst(['order_shipped_driver_id']);

        if (!$shipped) {
            return false;
        }

        if (!$accept) {
            return $this->rejectPreorder($driver_id, $order_id, $accept_hash, $shipped->order_shipped_driver_id);
        }

        return $this->acceptPreorder($driver_id, $order_id, $shipped->order_shipped_driver_id, $accept_hash);
    }

    /**
     * @param $driver_id
     * @param $order_id
     * @param $hash
     * @param $shipped_id
     * @return bool|array
     * @throws Exception
     */
    #[ArrayShape([
        'reject_rating' => 'int|float',
        'order_id' => 'int',
        'hash' => 'string',
    ])]
    public function rejectPreorder($driver_id, $order_id, $hash, $shipped_id): bool|array
    {
        $patterns = $this->estimatedRatingContract
            ->where('order_id', '=', $order_id)
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['remove_patterns', 'remove_rating']);

        if ($patterns) {
            $this->ratingService->setDriverRating($driver_id, $order_id, $patterns['remove_patterns']['ids']);
        }

        $this->driverContract->beginTransaction();
        if (!$this->shippedContract->update($shipped_id, ['status_id' => OrderShippedStatus::PRE_REJECTED, 'current' => false])) {
            $this->driverContract->rollBack();
            return false;
        }

        if (!$this->preorderContract->where('order_id', '=', $order_id)->updateSet(['active' => false])) {
            $this->driverContract->rollBack();
            return false;
        }

        if (!$this->commonContract->where('order_id', '=', $order_id)->updateSet(['accept' => false])) {
            $this->driverContract->rollBack();
            return false;
        }

        if (!$this->driverContract->update($driver_id, ['current_status_id' => DriverStatus::DRIVER_IS_FREE])) {
            $this->driverContract->rollBack();
            return false;
        }
        $this->driverContract->commit();

        CommonListTikTak::dispatch($order_id, $driver_id)->onQueue(ConstQueue::LONG()->getValue());

        return ['reject_rating' => $patterns['remove_rating'], 'order_id' => $order_id, 'hash' => $hash];
    }

    /**
     * @param $driver_id
     * @param $order_id
     * @param $shipped_id
     * @param $accept_hash
     * @return bool|array
     * @throws JsonException
     * @throws Exception
     */
    #[ArrayShape([
        'hash' => 'string',
        'order_id' => 'int',
    ])]
    public function acceptPreorder($driver_id, $order_id, $shipped_id, $accept_hash): bool|array
    {
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'car_id', 'current_franchise_id', 'phone']);
        $order = $this->orderContract->find($order_id, ['order_id', 'from_coordinates']);

        if (!($driver || $order)) {
            return false;
        }

        $new_hash = get_token();

        $client = $this->clientService->getOrderedClientData($order_id);
        $cord = $this->driverContract->getCordArray($driver_id);

        $result = $this->driverContract->beginTransaction(function () use ($driver_id, $shipped_id, $new_hash) {
            if (!$this->driverContract->update($driver_id, ['current_status_id' => DriverStatus::DRIVER_ON_WAY])) {
                return false;
            }

            if (!$this->shippedContract->update($shipped_id, ['in_place_hash' => $new_hash, 'current' => true])) {
                return false;
            }

            if (!$this->orderOnWayRoadContract->where('shipment_driver_id', '=', $shipped_id)->updateSet(['selected' => true])) {
                return false;
            }

            return true;
        });

        if (!$result) {
            throw new Lexcept('Server error accept order', 500);
        }

        $on_way_road = $this->orderOnWayRoadContract
            ->where('shipment_driver_id', '=', $shipped_id)
            ->findFirst(['order_on_way_road_id']);

        $this->taxiMapDistribution($client, $driver_id, $order_id, $on_way_road->order_on_way_road_id, $cord, $accept_hash);

        return ['hash' => $new_hash, 'order_id' => $order_id];
    }
}
