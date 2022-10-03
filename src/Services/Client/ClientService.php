<?php

declare(strict_types=1);


namespace Src\Services\Client;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;
use JsonException;
use ReflectionException;
use ServiceEntity\BaseService;
use Src\Broadcasting\Broadcast\Driver\ClientCurrentCord;
use Src\Broadcasting\Broadcast\Driver\CommonOrderEvent;
use Src\Core\Complex\GetClientState;
use Src\Core\Enums\ConstClientStatus;
use Src\Core\Enums\ConstRedis;
use Src\Exceptions\Lexcept;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Jobs\FindView\GetTaxiFromRadius;
use Src\Models\Client\BeforeAuthClient;
use Src\Models\Client\Client;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Repositories\BeforeAuthClient\BeforeAuthClientContract;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\ClientAddress\ClientAddressContract;
use Src\Repositories\ClientSetting\ClientSettingContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\Country\CountryContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\OrderStatus\OrderStatusContract;
use Src\Repositories\OrderType\OrderTypeContract;
use Src\Repositories\PayCard\PayCardContract;
use Src\Repositories\PaymentType\PaymentTypeContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Driver\DriverTrait;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Tariff\TariffServiceContract;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * Class ClientService
 * @package Src\Services\ClientMessage
 */
final class ClientService extends BaseService implements ClientServiceContract
{
    use ClientTrait;
    use DriverTrait;


    /**
     * @param ClientContract $clientContract
     * @param OrderContract $orderContract
     * @param OrderTypeContract $orderTypeContract
     * @param OrderStatusContract $orderStatusContract
     * @param PaymentTypeContract $paymentTypeContract
     * @param CarClassContract $carClassContract
     * @param DriverContract $driverContract
     * @param ClientAddressContract $clientAddressContract
     * @param BeforeAuthClientContract $beforeClientContract
     * @param CarServiceContract $carService
     * @param CountryContract $countryContract
     * @param OrderServiceContract $orderService
     * @param GeocodeServiceContract $geoService
     * @param ClientSettingContract $clientSettingContract
     * @param PayCardContract $payCardContract
     * @param TariffServiceContract $tariffService
     * @param PreorderContract $preOrderContract
     * @param OrderShippedDriverContract $shippedContract
     * @param OrderCommonContract $commonContract
     * @param CompanyContract $companyContract
     */
    public function __construct(
        protected ClientContract $clientContract,
        protected OrderContract $orderContract,
        protected OrderTypeContract $orderTypeContract,
        protected OrderStatusContract $orderStatusContract,
        protected PaymentTypeContract $paymentTypeContract,
        protected CarClassContract $carClassContract,
        protected DriverContract $driverContract,
        protected ClientAddressContract $clientAddressContract,
        protected BeforeAuthClientContract $beforeClientContract,
        protected CarServiceContract $carService,
        protected CountryContract $countryContract,
        protected OrderServiceContract $orderService,
        protected GeocodeServiceContract $geoService,
        protected ClientSettingContract $clientSettingContract,
        protected PayCardContract $payCardContract,
        protected TariffServiceContract $tariffService,
        protected PreorderContract $preOrderContract,
        protected OrderShippedDriverContract $shippedContract,
        protected OrderCommonContract $commonContract,
        protected CompanyContract $companyContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function profile()
    {
        $statuses = $this->orderStatusContract->findAll();
        $types = $this->orderTypeContract->findAll();
        $client = $this->clientContract->find(get_user_id());

        if (!$statuses || !$types) {
            return false;
        }

        return compact('statuses', 'types', 'client');
    }

    /**
     * @inheritDoc
     */
    public function getOrders(array $request): LengthAwarePaginator
    {
        $perPage = $request['per_page'] ?? 10;
        $dateStart = $request['date_start'] ?? null;
        $dateEnd = $request['date_end'] ?? null;
        $statusId = $request['status'] ?? null;
        $orderTypeId = $request['type'] ?? null;
        $sortBy = $request['sortBy'] ?? '';

        return $this->orderContract
            ->has('completed')
            ->where('client_id', '=', auth()->id(), 'or')
            ->orWhereHas('passenger', fn(Builder $query) => $query->where('passenger_id', '=', get_user_id()))
            ->when($dateStart, fn($query) => $query->where('created_at', '>=', $dateStart))
            ->when($dateEnd, fn($query) => $query->where('created_at', '<=', $dateEnd))
            ->when($statusId, fn($query) => $query->where('status_id', '=', $statusId))
            ->when($orderTypeId, fn($query) => $query->where('order_type_id', '=', $orderTypeId))
            ->when($sortBy, fn($query) => $query->orderBy($sortBy, 'desc'))
            ->when(!$sortBy, fn($query) => $query->orderByDesc('created_at'))
            ->with([
                'paymentType' => fn($query) => $query->select(['*']),
                'completed' => fn($query) => $query->select(['*']),
                'status' => fn($query) => $query->select(['*']),
                'preorder' => fn($query) => $query->select(['*']),
                'in_process_road' => fn($query) => $query->select(['order_in_process_road_id', 'real_road', 'duration', 'distance']),
                'stage' => fn($query) => $query->select(['*']),
                'company' => fn($query) => $query->select(['*']),
                'passenger' => fn($query) => $query->select(['*']),
                'initial_data' => fn($query) => $query->select(['*']),
            ])
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getClientMobileOrders($client_id, $skip, $take): Collection
    {
        $client = $this->clientContract
            ->with([
                'completed_orders' => fn(HasManyThrough $query) => $query
                    ->with([
                        'stage' => fn($query) => $query->select(['order_stage_cord_id', 'started', 'ended']),
                        'order' => fn($query) => $query->select(['order_id', 'address_from', 'address_to'])
                    ])
                    ->offset($skip)
                    ->limit($take)
                    ->select(['completed_order_id', 'completed_orders.order_id', 'cost', 'destination_address', 'completed_orders.created_at']),
            ])
            ->find($client_id);

        return $client->completed_orders ?? null ? $client->completed_orders->sortBy('created_at', SORT_NATURAL, true) : collect();
    }

    /**
     * @inheritdoc
     */
    public function createClientCallCenter($request): ?object
    {
        $data = [
            'phone' => $request['phone'],
            'email' => $request['email'],
            'name' => $request['name'],
            'surname' => $request['surname'],
            'patronymic' => $request['patronymic'],
        ];

        return $this->clientContract->create($data);
    }

    /**
     * @param $client_id
     * @param $request
     * @return false|mixed
     */
    public function updateClientCallCenter($client_id, $request)
    {
        $client = $this->clientContract->find($client_id);

        if (!$client) {
            return false;
        }

        $data = [
            'phone' => $request['phone'],
            'email' => $request['email'],
            'name' => $request['name'],
            'surname' => $request['surname'],
            'patronymic' => $request['patronymic'],
        ];

        $this->clientContract->update($client_id, $data);

        return $client;
    }

    /**
     * @inheritDoc
     */
    public function getFavoriteDrivers($client_id): \Illuminate\Database\Eloquent\Collection
    {
        return $this->clientContract
            ->with(['favoriteDrivers' => fn($q) => $q->where('client_id', '=', $client_id)->select('name', 'nickname', 'phone')])
            ->find($client_id, ['client_id']);
    }

    /**
     * @inheritDoc
     */
    public function getCompanies($client_id)
    {
        $clinet = $this->clientContract
            ->with([
                'corporateCompanies' => fn($q) => $q
                    ->where('client_id', '=', $client_id)
                    ->select('companies.company_id', 'companies.limit', 'companies.name', 'companies.address'),
            ])
            ->find($client_id, ['client_id'])->toArray();

        foreach ($clinet['corporate_companies'] as $key => $value) {
            $phoneMask = $this->getCompanyPhoneMask($value['company_id']);
            $clinet['corporate_companies'][$key]['phone_mask'] = $phoneMask;
        }

        return $clinet;
    }

    /**
     * @param $company_id
     * @return mixed
     */
    protected function getCompanyPhoneMask($company_id)
    {
      return $this->companyContract->where('company_id','=',$company_id)
            ->with(['country' => fn($q) => $q->select(['phone_mask'])])->findFirst(['franchise_id'])['country']['phone_mask'];
    }

    /**
     * @inheritdoc
     */
    public function getClientByPhone($phone, array $values = ['*'])
    {
        return $this->clientContract
            ->where('phone', '=', $phone)
            ->findFirst($values); //@todo fix select
    }

    /**
     * @param $phone
     * @param  array  $values
     * @return mixed
     */
    public function getClientById($client_id, array $values = [])
    {
        return $this->clientContract->find($client_id, empty($values) ? ['*'] : $values);
    }

    /**
     * @param $phone
     * @return array
     */
    public function callCenterCheckClientExists($phone): array
    {
        $client = $this->clientContract
            ->where('phone', '=', preg_replace('/\D/', '', $phone))
            ->findFirst(['client_id', 'phone']);

        if (!$client) {
            return ['exists' => false];
        }

        $companies = $client->corporateCompanies()->get();
        $orders = $this->orderService->getLastOrders($client->client_id);

        return [
            'exists' => true,
            'client' => $client,
            'companies' => $companies,
            'orders' => $orders
        ];
    }

    /**
     * @param $phone
     * @return array
     */
    public function callCenterCheckPassengerExists($phone): array
    {
        $passenger = $this->clientContract
            ->where('phone', '=', preg_replace('/\D/', '', $phone))
            ->findFirst();

        if (!$passenger) {
            return ['exists' => false];
        }

        return [
            'exists' => true,
            'passenger' => $passenger,
        ];
    }

    /**
     * @param $client_id
     * @return mixed
     */
    public function getAddresses($client_id)
    {
        return $this->clientAddressContract
            ->where('client_id', '=', $client_id)
            ->orderBy('client_address_id', 'desc')
            ->findAll();
    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function getOrderInfo($user, array $cord = []): array
    {
        return $this->getClientInitData($user, $cord);
    }

    /**
     * @inheritDoc
     */
    public function getPhoneMask(Model $user, $country_lat = null, $lut = null): ?string
    {
        if ($user->getMap() !== (new Client())->getMap()) {
            return session('app_system.mask');
        }

        if ($lut) {
            $geo = $this->geoService->getRightGeocode([$country_lat, $lut]);
            $country = $this->countryContract
                ->has('franchisee')
                ->firstWhere(['iso_2', '=', $geo['country_code']], ['name', 'phone_mask', 'country_id', 'iso_2']);
        } else {
            $country = $this->countryContract->has('franchisee')->firstWhere(['name', '=', $country_lat->country], ['name', 'phone_mask', 'country_id']);
        }

        if ($country) {
            return $country->phone_mask;
        }

        $country = $this->countryContract->has('franchisee')->firstWhere(['name', '=', 'Russia'], ['name', 'phone_mask']);

        return $country->phone_mask;
    }

    /**
     * @inheritDoc
     */
    public function createAddress($address)
    {
        $client = $this->clientContract->find(auth()->id());

        if (!$client) {
            return false;
        }

        $result = $this->clientAddressContract->create($address);

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * @param $address  ,
     * @param $client_address_id  ,
     * @return mixed
     */
    public function updateAddress($address, $id)
    {
        $address = $this->clientAddressContract->update($id, $address);

        if ($address) {
            return $address;
        }

        return false;
    }

    /**
     * @param $address  ,
     * @param $client_address_id  ,
     * @return mixed
     */
    public function updateAddressFavorite($address, $client_address_id)
    {
        $address = $this->clientAddressContract->update($client_address_id, $address);

        if ($address) {
            return $address;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteAddress($client_id, $client_address_id)
    {
        $client = $this->clientContract->find($client_id);

        if (!$client) {
            return false;
        }

        $result = $this->clientAddressContract
            ->where('client_address_id', '=', $client_address_id)
            ->delete($client_address_id);

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * @param $phone
     * @return mixed
     */
    public function createClient($phone)
    {
        return $this->clientContract->create(['phone' => $phone]);
    }

    /**
     * @param $order_id
     * @param  array|string[]  $values
     * @return object|null
     */
    public function getOrderedClientData($order_id, array $values = ['*']): ?object
    {
        $order = $this->orderContract
            ->where($this->orderContract->getKeyName(), '=', $order_id)
            ->with(['client:'.implode(',', $values), 'passenger:'.implode(',', $values)])
            ->findFirst([$this->orderContract->getKeyName(), 'client_id']);

        return $order ? $order->passenger ?? $order->client : null;
    }

    /**
     * @inheritDoc
     */
    public function getCorrectCoordinate($client_id, string $client_type = 'client', int $order_id = null): ?array
    {
        $contract = $this->getClientContract($client_type);
        $lat = null;

        $client = $contract
            ->with([
                'initial_order_data' => fn($query) => $query
                    ->when(
                        $order_id,
                        fn($query) => $query
                            ->where('order_id', '=', $order_id)
                            ->select([
                                'order_initial_data_id',
                                'order_id',
                                'initial_tariff_id',
                                'orderable_id',
                                'orderable_type',
                                'lat',
                                'lut'
                            ]),
                    )
                    ->when(
                        !$order_id,
                        fn($query) => $query
                            ->where('order_id', '=', null)
                            ->select([
                                'order_initial_data_id',
                                'order_id',
                                'initial_tariff_id',
                                'orderable_id',
                                'orderable_type',
                                'lat',
                                'lut'
                            ]),
                    )
            ])
            ->when(($order_id && $contract === $this->clientContract),
                fn($query) => $query
                    ->with(['current_order' => fn($query) => $query->select(['order_id', 'client_id', 'from_coordinates', 'show_cord', 'lat', 'lut'])])
            )
            ->find($client_id, [$contract->getKeyName()]);

        if ($client) {
            if ($client->current_order) {
                $lat = $client->current_order->show_cord ? $client->current_order->lat : $client->current_order->from_coordinates['lat'];
                $lut = $client->current_order->show_cord ? $client->current_order->lut : $client->current_order->from_coordinates['lut'];
            }

            if ($client->initial_order_data) {
                $lat = $client->initial_order_data->lat;
                $lut = $client->initial_order_data->lut;
            }
        }

        return $lat ? compact('lat', 'lut') : null;
    }

    /**
     * @inheritDoc
     */
    public function getClientContract($type)
    {
        if ('client' === $type) {
            return $this->clientContract;
        }

        return $this->beforeClientContract;
    }

    /**
     * @inheritDoc
     */
    public function clientIsAuth($type): ?bool
    {
        return (new Client())->getMap() === $type;
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     */
    public function clientOnlineMaster(Model $client, $status)
    {
        $client_contract = $this->getClientContract($client->getMap());
        $clients = $client_contract->find($client->{$client->getKeyName()});

        if ($status) {
            $client_contract->update($client->{$client->getKeyName()}, ['online' => 1]);
            return $this->clientOnlineMasterOnline($clients);
        }

        try {
            $this->clientOnlineMasterOffline($clients);
        } catch (Exception $exception) {
            throw new Lexcept('Server error', 500);
        }

        return true;
    }

    /**
     * @param  Model  $client
     * @return Collection
     */
    protected function clientOnlineMasterOnline(Model $client): Collection
    {
        if ($client->in_order) {
            $client->load([
                'current_order' => fn(HasOne $order_query) => $order_query
                    ->select([
                        'orders.order_id',
                        'client_id',
                        'orders.car_class_id',
                        'address_from',
                        'address_to',
                        'from_coordinates',
                        'to_coordinates',
                        'payment_type_id',
                        'status_id',
                        'created_at'
                    ]),
                'initial_order' => fn($q) => $q->where('status_id', '=', 1)
                    ->select([
                        'order_initial_data_id',
                        'price',
                        'currency',
                        'initial',
                        'sitting_fee',
                        'sitting_price',
                        'distance',
                        'option_price',
                    ])
            ]);

            if (!$client->current_order) {
                return $this->parseResult($instance);
            }

            if ($client->current_order->status_id === OrderStatus::ORDER_PENDING) {
                return $this->parseResult($instance, ['status', 'order', 'action'],
                    [OrderStatus::ORDER_PENDING, $client->current_order, $client->initial_order]);
            }

            if ($client->current_order->status_id === OrderStatus::ORDER_IN_PROCESS) {
                $shipment = $this->orderContract
                    ->with([
                        'ordering_shipment' => fn(HasOne $shipment_query) => $shipment_query
                            ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                            ->with('driver:driver_id')
                            ->select(['order_shipped_driver_id', 'driver_id', 'order_id', 'status_id']),
                        'initial_data' => fn($q) => $q->select([
                            'order_initial_data_id',
                            'order_id',
                            'price',
                            'currency',
                            'initial',
                            'sitting_fee',
                            'sitting_price',
                            'distance',
                            'option_price',
                        ])
                    ])
                    ->find($client->current_order->order_id, ['client_id', 'status_id', 'from_coordinates', 'to_coordinates', 'order_id']);

                $driver = $this->getDriverUpdatedData($shipment->ordering_shipment->driver->driver_id);

                return $this->parseResult(
                    $instance,
                    ['status', 'order', 'driver', 'in_order', 'action'],
                    [OrderStatus::ORDER_IN_PROCESS, $client->current_order, $driver, $client->in_order, $shipment->initial_data]
                );
            }
        }

        return $this->parseResult(
            $instance,
            ['client_id', 'name', 'surname', 'phone', 'in_order'],
            [$client->client_id, $client->name, $client->surname, $client->phone, $client->in_order]
        );
    }

    /**
     * @param  Model  $client
     */
    protected function clientOnlineMasterOffline(Model $client): void
    {
        $this->clientContract->update($client->{$client->getKeyName()}, ['online' => 0]);

        if ($client::has('drivers_road_view')->exists() && $client::has('coordinate')->exists() && $client::has('drivers_view')->exists()) {
            $client->drivers_view()->delete();
        }
    }


    /**
     * @param $client_id
     * @return array
     */
    public function getClientCompanies($client_id)
    {
        $client = $this->clientContract->with('corporateCompanies')->find($client_id);
        return $client->corporateCompanies ?: [];
    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function openMobileApp(Client $client, array $cords): array
    {
        GetTaxiFromRadius::dispatch($cords['lat'], $cords['lut'], $client);
        $this->clientContract->update($client->client_id, ['online' => true]);

        return $this->getClientInitData($client, $cords);
    }

    /**
     * @inheritDoc
     * @noinspection MultipleReturnStatementsInspection
     * @throws ReflectionException
     */
    public function getRealState($client): ?array
    {
        if ($client->getMap() === (new BeforeAuthClient())->getMap()) {
            return ['status' => ConstClientStatus::stateless()->getValue(), 'payload' => []];
        }

        $client = $this->clientContract->find($client->client_id, ['client_id', 'in_order']);

        if (!$client->in_order && !$this->hasOrderWithoutAssessment($client->client_id)) {
            return ['status' => ConstClientStatus::stateless()->getValue(), 'payload' => []];
        }

        return GetClientState::complex($client);
    }

    /**
     * @inheritDoc
     */
    public function hasOrderWithoutAssessment($client_id): bool|int
    {
        $client = $this->clientContract
            ->with([
                'orders' => fn(HasMany $query) => $query
                    ->where('status_id', '=', OrderStatus::getStatusId(OrderStatus::ORDER_COMPLETED))
                    ->whereDoesntHave('assessment')
                    ->latest('created_at')
                    ->select(['order_id', 'client_id', 'status_id'])
            ])
            ->find($client_id, ['client_id']);

        if (!$client && !$client->orders->count()) {
            return false;
        }

        return $client->orders->first() ? $client->orders->first()->order_id : false;
    }

    /**
     * @inheritDoc
     */
    public function showMyCordsInOrder($client_id, bool $show = false, array $cord = []): void
    {
        $client_order = $this->clientContract
            ->with([
                'current_order' => fn(HasOne $query) => $query->select(['orders.order_id', 'orders.status_id', 'from_coordinates', 'address_from', 'client_id'])
            ])
            ->when($show, fn($query) => $query->with([
                'current_order_driver' => fn(HasOneDeep $query) => $query->select([
                    'drivers.driver_id',
                    'drivers.current_status_id',
                    'drivers.lat',
                    'drivers.lut',
                    'drivers.azimuth'
                ])
            ]))
            ->find($client_id, ['client_id']);

        if (!$client_order && !$client_order->current_order) {
            return;
        }

        if ($show) {
            $this->orderContract->update($client_order->current_order->order_id, ['show_cord' => $show, 'lat' => $cord['lat'], 'lut' => $cord['lut']]);
        } else {
            $this->orderContract->update($client_order->current_order->order_id, ['show_cord' => $show, 'lat' => null, 'lut' => null]);
        }

        ClientCurrentCord::broadcast($client_order->current_order_driver, $cord, $show);
    }

    /**
     * @inheritDoc
     */
    public function getOrderDetail($order_id, $client_id): ?object
    {
        return $this->clientContract
            ->with([
                'completed_order' => fn(HasOneThrough $query) => $query
                    ->where('completed_orders.order_id', '=', $order_id)
                    ->with([
                        'stage' => fn($query) => $query->select(['order_stage_cord_id', 'started', 'ended']),
                        'driver_info' => fn($query) => $query->select(['drivers_info.driver_info_id', 'name', 'surname']),
                        'driver' => fn($query) => $query->select(['drivers.driver_id', 'phone']),
                        'car' => fn($query) => $query->select(['car_id', 'mark', 'model', 'color', 'state_license_plate']),
                    ])
                    ->select(['completed_order_id', 'completed_orders.order_id', 'cost', 'destination_address', 'driver_id', 'car_id', 'trajectory']),
                'order' => fn(HasOne $query) => $query
                    ->where('order_id', '=', $order_id)
                    ->with([
                        'paymentType' => fn($query) => $query->select(['payment_type_id', 'type', 'name']),
                        'carClass' => fn($query) => $query->select(['car_class_id', 'class_name']),
                    ])
                    ->select(['order_id', 'client_id', 'address_from', 'address_to', 'payment_type_id', 'car_class_id'])
            ])
            ->find($client_id, ['client_id']);
    }

    /**
     * @inheritDoc
     */
    public function getClientAddresses(int $client_id, bool $favorite = false): Collection
    {
        return $this->clientAddressContract
            ->when($favorite, fn(Builder $query) => $query->where('favorite', '=', true))
            ->where('client_id', '=', $client_id)
            ->findAll();
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function addClientAddresses(int $client_id, array $payload): ?int
    {
        $create = $this->clientAddressContract->create([
            'client_id' => $client_id,
            'name' => $payload['name'],
            'short_address' => $payload['short_address'],
            'address' => $payload['address'],
            'favorite' => $payload['favorite'],
            'coordinates' => decode($payload['cord'])
        ]);

        return $create->client_address_id ?? null;
    }

    /**
     * @inheritDoc
     */
    public function editClientAddress($client_id, $address_id, $payload): void
    {
        $this->clientAddressContract->where('client_address_id', '=', $address_id)->updateSet($payload);
    }

    /**
     * @inheritDoc
     */
    public function deleteClientAddress($client_id, $address_id): void
    {
        $this->clientAddressContract->where('client_id', '=', $client_id)->delete($address_id);
    }

    /**
     * @param $client_id
     * @return mixed
     */
    public function getSettings($client_id)
    {
        $client = $this->clientContract->with(['setting'])->find($client_id, ['client_id']);

        return $client->setting;
    }

    /**
     * @param $client_id
     * @param  array  $settings
     * @return void
     */
    public function editSettings($client_id, array $settings): void
    {
        $this->clientSettingContract->where('client_id', '=', $client_id)->updateSet($settings);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function deletePreorder($client_id, $order_id): bool
    {
        $has_driver = $this->orderContract
            ->where('order_id', '=', $order_id)
            ->where('client_id', '=', $client_id)
            ->where('status_id', '=', OrderStatus::getStatusId(OrderStatus::ORDER_PENDING))
            ->with(['ordering_shipment' => fn($query) => $query->select(['order_shipped_driver_id', 'driver_id', 'order_id'])])
            ->findFirst(['order_id', 'client_id']);

        $this->orderContract->beginTransaction();
        if (!$this->orderContract->where('order_id', '=', $order_id)->updateSet(['status_id' => OrderStatus::getStatusId(OrderStatus::ORDER_CANCELED)])) {
            $this->orderContract->rollBack();
            return false;
        }

        if (!$this->preOrderContract->where('order_id', '=', $order_id)->updateSet(['active' => false])) {
            $this->orderContract->rollBack();
            return false;
        }
        $this->orderContract->commit();

        if ($has_driver && $has_driver->ordering_shipment) {
            $driver = $this->orderService->getOrderDriver($order_id);
            CommonOrderEvent::broadcast($driver, new PassOrderResource(['order_id' => $order_id]), 'delete');
        }

        return true;
    }

    public function updateClientInfo($data, $client_id)
    {
        return $this->clientContract->update($client_id, $data);
    }

    public function updatePassword($client_id, $data)
    {
        $this->clientContract->update($client_id, $data);
    }

    public function addPassword($client_id, $data)
    {
        $this->clientContract->update($client_id, $data);
    }

    public function getClientCompanyMask($clientCompany)
    {
        return $this->countryContract->where('name', '=', $clientCompany)->findFirst('phone_mask');
    }

    /**
     * @inheritdoc
     */
    public function getPreOrders($client_id, int $skip = null, int $take = null): Collection
    {
        return $this->preOrderContract
            ->where('active', '=', true)
            ->where('time', '>', now())
            ->whereHas('order', fn(Builder $query) => $query
                ->where('client_id', '=', $client_id)
                ->where('status_id', '=', OrderStatus::ORDER_PENDING)
            )
            ->with([
                'initial' => fn($query) => $query->select(['order_id', 'price', 'currency', 'distance', 'duration']),
                'order' => fn($query) => $query->select(['order_id', 'from_coordinates', 'to_coordinates', 'address_from', 'address_to']),
                'shipped_driver' => fn($query) => $query
                    ->where('status_id', '=', OrderShippedStatus::PRE_PENDING)
                    ->orWhere('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                    ->with(['driver_info' => fn($query) => $query->select(['driver_info_id', 'name', 'surname', 'patronymic', 'photo'])])
                    ->select(['drivers.driver_id', 'drivers.driver_info_id']),
            ])
            ->orderBy('time', 'DESC')
            ->when($skip, fn(Builder $query) => $query->offset($skip))
            ->when($take, fn(Builder $query) => $query->limit($take))
            ->findAll(['preorder_id', 'order_id', 'create_time', 'time', 'diff_minute']);
    }

    /**
     * @throws Exception
     */
    public function cancelPreOrder($client_id, $order_id): bool
    {
        $this->clientContract->beginTransaction();

        if (!$this->orderContract->update($order_id, ['status_id' => OrderStatus::ORDER_CANCELED])) {
            $this->clientContract->rollBack();
        }

        if (!$this->preOrderContract->where('order_id', '=', $order_id)->updateSet(['active' => false])) {
            $this->clientContract->rollBack();
        }

        if (!$this->commonContract->where('order_id', '=', $order_id)->updateSet(['active' => false])) {
            $this->clientContract->rollBack();
        }
        $this->clientContract->commit();

        $shipped = $this->shippedContract
            ->where('order_id', '=', $order_id)
            ->where('current', '=', false)
            ->where('common', '=', true)
            ->where(fn(Builder $query) => [
                $query
                    ->where('status_id', '=', OrderShippedStatus::PRE_PENDING)
                    ->orWhere('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
            ])
            ->firstLatest('created_at', ['order_id', 'driver_id', 'order_shipped_driver_id']);

        if ($shipped) {
            if ($shipped_driver = $this->driverContract->find($shipped->driver_id, ['driver_id', 'car_id', 'phone', 'current_franchise_id'])) {
                CommonOrderEvent::broadcast($shipped_driver, new PassOrderResource(['order_id' => $order_id]), 'delete');
            }

            $this->shippedContract->update($shipped->order_shipped_driver_id, ['status_id' => OrderShippedStatus::PRE_CANCELED]);
        }

        $pre_commons = $this->commonContract
            ->where('order_id', '=', $order_id)
            ->firstLatest('order_common_id', ['order_id', 'driver']);

        if ($pre_commons && $pre_commons->driver['ids']) {
            $received_drivers = $this->driverContract
                ->whereIn('driver_id', $pre_commons->driver['ids'])
                ->findAll(['driver_id', 'car_id', 'phone', 'current_franchise_id']);

            foreach ($received_drivers as $driver) {
                CommonOrderEvent::broadcast($driver, new PassOrderResource(['order_id' => $order_id]), 'delete');
            }
        }

        $this->removeRedisKeys($order_id);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function changePrOrderDate($order_id, $date): bool
    {
        $new_date = Carbon::parse($date)->format('Y:m:d H:i:s');

        $this->preOrderContract
            ->where('order_id', '=', $order_id)
            ->updateSet(['time' => $new_date, 'changed' => DB::raw('changed+1')]);

        $shipped = $this->shippedContract
            ->where('order_id', '=', $order_id)
            ->where('common', '=', true)
            ->where(fn(Builder $query) => $query
                ->where('status_id', '=', OrderShippedStatus::PRE_PENDING)
                ->orWhere('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
            )
            ->with(['driver' => fn($query) => $query->select(['driver_id', 'car_id', 'current_franchise_id', 'phone'])])
            ->findFirst(['order_id', 'driver_id']);

        $order_data = igus($this->redis()->hMGet(ConstRedis::order_create_data($order_id), ['order_data'])[0]);
        $order_data['preorder']['time'] = $new_date;
        $new_payload = $order_data;
        $this->redis()->hMSet(ConstRedis::order_create_data($order_id), ['order_data' => igs($new_payload)]);

        if ($shipped && $shipped->driver) {
            CommonOrderEvent::dispatch($shipped->driver, new PassOrderResource(['order_id' => $order_id, 'delivery_time' => $new_date]), 'change');
        } else {
            $received = $this->preOrderContract
                ->where('order_id', '=', $order_id)
                ->with([
                    'drivers' => fn($query) => $query->select(['driver_id', 'car_id', 'current_franchise_id', 'phone'])
                ])
                ->findFirst(['order_id', 'driver']);

            if ($received && $received->drivers) {
                foreach ($received->drivers as $driver) {
                    CommonOrderEvent::dispatch($driver, new PassOrderResource(['order_id' => $order_id, 'delivery_time' => $new_date]), 'change');
                }
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getPreorderLimit($client_id): bool
    {
        return $this->preOrderContract
                ->where('active', '=', true)
                ->whereHas('order', fn(Builder $query) => $query->where('client_id', '=', $client_id))
                ->count() >= 5;
    }
}
