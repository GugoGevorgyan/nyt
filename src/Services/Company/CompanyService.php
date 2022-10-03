<?php

declare(strict_types=1);


namespace Src\Services\Company;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ServiceEntity\BaseService;
use Src\Core\Additional\GenerateXSLX;
use Src\Models\Order\Order;
use Src\Models\Order\PaymentType;
use Src\Repositories\AdminCorporate\AdminCorporateContract;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\City\CityContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\CompanyPhone\CompanyPhoneContract;
use Src\Repositories\CompanyReport\CompanyReportContract;
use Src\Repositories\CompletedOrder\CompletedOrderContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCorporate\OrderCorporateContract;
use Src\Repositories\OrderStatus\OrderStatusContract;
use Src\Repositories\OrderType\OrderTypeContract;
use Src\Repositories\RailwayStation\RailwayStationContract;
use Src\Repositories\Region\RegionContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\OrderEnd\OrderEndServiceContract;

/**
 * Class CompanyService
 * @package Src\Services\Company
 */
final class CompanyService extends BaseService implements CompanyServiceContract
{
    /**
     * CompanyService constructor.
     * @param  CompanyContract  $companyContract
     * @param  OrderContract  $orderContract
     * @param  OrderTypeContract  $orderTypeContract
     * @param  OrderStatusContract  $orderStatusContract
     * @param  CarClassContract  $carClassContract
     * @param  CompanyPhoneContract  $companyPhoneContract
     * @param  AdminCorporateContract  $adminCorporateContract
     * @param  GeocodeServiceContract  $geoService
     * @param  RegionContract  $regionContract
     * @param  ClientServiceContract  $clientService
     * @param  InitialOrderDataContract  $initialContract
     * @param  AirportContract  $airportContract
     * @param  RailwayStationContract  $stationContract
     * @param  CityContract  $cityContract
     * @param  CompanyReportContract  $companyReportContract
     * @param  OrderEndServiceContract  $orderEndService
     * @param  CompletedOrderContract  $orderCompletedContract
     * @param  OrderCorporateContract  $orderCorporateContract
     * @param  FranchiseContract $franchiseContract
     */
    public function __construct(
        protected CompanyContract $companyContract,
        protected OrderContract $orderContract,
        protected OrderTypeContract $orderTypeContract,
        protected OrderStatusContract $orderStatusContract,
        protected CarClassContract $carClassContract,
        protected CompanyPhoneContract $companyPhoneContract,
        protected AdminCorporateContract $adminCorporateContract,
        protected GeocodeServiceContract $geoService,
        protected RegionContract $regionContract,
        protected ClientServiceContract $clientService,
        protected InitialOrderDataContract $initialContract,
        protected AirportContract $airportContract,
        protected RailwayStationContract $stationContract,
        protected CityContract $cityContract,
        protected CompanyReportContract $companyReportContract,
        protected OrderEndServiceContract $orderEndService,
        protected CompletedOrderContract $orderCompletedContract,
        protected OrderCorporateContract $orderCorporateContract,
        protected FranchiseContract $franchiseContract,
    ) {
    }


    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        $company = $this->companyContract->find(user()->company_id);

        if ($this->issetContract($company->contract_start) > 0) {
            $limit_value = $this->checkCompanyLimit($company);


            if (!$limit_value) {
                return ['message' => trans('messages.order_limit_corporate')];
            }

            $statuses = $this->orderStatusContract->findAll();
            $types = $this->orderTypeContract->find([Order::ORDER_TYPE_CLIENT_BY_COMPANY, Order::ORDER_TYPE_COMPANY_TO_CLIENT]);
            $carClasses = $this->carClassContract->findAll();

            if (!$company || !$statuses || !$types || !$carClasses) {
                return null;
            }

            return compact('company', 'statuses', 'types', 'carClasses');
        } else {
            return ['message' => trans('messages.contract_d_not_have')];
        }
    }

    public function issetContract($contract_date): int
    {
        return Carbon::parse($contract_date)->diffInDays(now()->format('Y-m-d'), false);
    }

    public function checkCompanyLimit($company)
    {
        $contract_start = $company->contract_start;

        $contract_end_this_month = Carbon::parse($contract_start)->month((int)now()->format('m'))
            ->year((int)now()->format('Y'))
            ->format('Y-m-d');

        $this_month_days_different = Carbon::parse($contract_end_this_month)->diffInDays(now()->format('Y-m-d'), false);

        $contract_end_last_month = Carbon::parse($contract_start)->month((int)now()->subMonth()->format('m'))
            ->year((int)now()->format('Y'))
            ->format('Y-m-d');

        $calculate_day = $this_month_days_different > 0 ? $contract_end_this_month : $contract_end_last_month;
        $order_corporate = $this->orderCorporateContract->where('company_id', '=', $company->company_id)
            ->with(['completed' => fn($q) => $q->where('created_at', '>=', $calculate_day)])->findAll();

        $costs = 0;
        foreach ($order_corporate as $key) {
            $cost = $key['completed']->toArray() ? $key['completed'][0]['cost'] : 0;
            $costs += $cost;
        }

        return $costs < $company->limit ? true : false;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->companyContract->find(user()->company_id);
    }

    /**
     * @param $company_id
     * @return mixed
     */
    public function getFranchiseCompany($company_id)
    {
        return $this->companyContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->with(['phones', 'corporateAdmins'])
            ->find($company_id);
    }

    /**
     * @param  array  $request
     * @param  int  $id
     * @return bool|null
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    public function updateCompanyFranchise(array $request, int $id): ?bool
    {
        $company = $this->companyContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->find($id, ['company_id', 'contract_scan']);

        if (!$company) {
            return null;
        }

        $this->companyContract->beginTransaction();
        $this->companyContract->forgetCache();

        if (!$this->companyContract->update($id, $this->prepareCrudData($request['company'], FRANCHISE_ID, $company))) {
            $this->companyContract->rollBack();
            return null;
        }

        if (isset($request['company']['admin_added'])
            && $request['company']['admin_added']
            && !$this->createAdminCorporate($request['adminCorporate'], $company->company_id, FRANCHISE_ID)) {
            $this->companyContract->rollBack();

            return null;
        }

        if (!$this->companyPhones($request['company'], $company)) {
            $this->companyContract->rollBack();
            return null;
        }

        $this->companyContract->commit();

        return true;
    }

    /**
     * @param $requestData
     * @param $franchise_id
     * @param  null  $company
     * @return array
     */
    protected function prepareCrudData($requestData, $franchise_id, $company = null): array
    {
        $data = [
            'name' => $requestData['name'],
            'address' => $requestData['address'],
            'franchise_id' => $franchise_id,
            'entity_id' => $requestData['entity_id'],
            'email' => $requestData['email'],
            'details' => $requestData['details'],
            'order_canceled_timeout' => $requestData['order_canceled_timeout'],
            'period' => $requestData['period'],
            'frequency' => $requestData['frequency'],
            'limit' => $requestData['limit'],
            'code' => $requestData['code'],
            'contract_start' => $requestData['contract_start'],
            'contract_end' => $requestData['contract_end'],
        ];

        if (isset($requestData['contract_scan_file']) && $requestData['contract_scan_file']) {
            $path = storage_path(DS.'public'.DS.'company'.DS.'contract-scans'.DS);
            $data['contract_scan'] = '/storage/company/contract-scans/'.$this->fileUpload($requestData['contract_scan_file'], $path);
            $company ? $this->deleteOldFile($company['contract_scan']) : null;
        }

        return $data;
    }

    /**
     * @param $data
     * @param $company_id
     * @param $franchise_id
     * @return object|null
     */
    protected function createAdminCorporate($data, $company_id, $franchise_id): ?object
    {
        $data['franchise_id'] = $franchise_id;
        $data['company_id'] = $company_id;
        $data['phone'] = preg_replace('/[\D]/', '', $data['phone']);
        $data['password'] = Hash::make($data['password']);

        return $this->adminCorporateContract->create($data);
    }

    /**
     * @param $data
     * @param $company
     * @return bool
     */
    protected function companyPhones($data, $company): bool
    {
        try {
            $this->companyPhoneContract->where('company_id', '=', $company->company_id)->deletes();
            $this->companyPhoneContract->create(['company_id' => $company->company_id, 'number' => preg_replace('/[\D]/', '', $data['phone'])]);

            if (isset($data['additional_phones'])) {
                foreach ($data['additional_phones'] as $phone) {
                    if ($phone) {
                        $this->companyPhoneContract->create(['company_id' => $company->company_id, 'number' => preg_replace('/[\D]/', '', $phone)]);
                    }
                }
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function createCompanyFranchise($request): ?bool
    {
        $this->companyContract->beginTransaction();
        $this->companyContract->forgetCache();

        $company = $this->companyContract->create($this->prepareCrudData($request['company'], FRANCHISE_ID));

        if (!$company) {
            $this->companyContract->rollBack();
            return null;
        }

        if ($request['company']['admin_added'] && !$this->createAdminCorporate($request['adminCorporate'], $company->company_id, FRANCHISE_ID)) {
            $this->companyContract->rollBack();
            return null;
        }

        if (!$this->companyPhones($request['company'], $company)) {
            $this->companyContract->rollBack();
            return null;
        }

        $this->companyContract->commit();

        return true;
    }

    /**
     * @param $request
     * @return Collection|mixed
     * @throws Exception
     */
    public function getOrdersPaginate($request): Collection
    {
        $per_page = $request['per-page'] ?: 10;
        $date_start = $request->date_start ?: false;
        $date_end = $request->date_end ? (new Carbon($request->date_end))->addDay()->format('Y-m-d') : false;
        $status_id = $request->status ?: false;
        $order_type_id = $request->type ?: false;

        $sort_by = $request->sortBy ?: '';
        $sort_desc = 0 != $request->sortDesc;
        $company_id = user()->company_id;

        $orders = $this->orderContract
            ->whereHas('corporate', fn(Builder $query) => $query->where('company_id', '=', $company_id))
            ->where('payment_type_id', '=', PaymentType::getTypeId(PaymentType::COMPANY))
            ->when($date_start, fn($query) => $query->where('created_at', '>=', $date_start))
            ->when($date_end, fn($query) => $query->where('created_at', '<=', $date_end))
            ->when($status_id, fn($query) => $query->where('status_id', '=', $status_id))
            ->when($order_type_id, fn($query) => $query->where('order_type_id', '=', $order_type_id))
            ->when($sort_desc, fn(Builder $query) => $query->orderByDesc('created_at'))
            ->when($sort_by, fn(Builder $query) => $query->orderBy('created_at'))
            ->with([
                'status' => fn($query) => $query->select(['*']),
                'stage' => fn($query) => $query->select(['*']),
                'passenger' => fn($query) => $query->select(['client_id', 'phone', 'name', 'surname', 'patronymic']),
                'process' => fn(HasOneThrough $query) => $query->select(['order_process_id', 'order_shipped_id', 'distance_traveled', 'travel_time']),
                'on_way_road' => fn(HasOneThrough $query) => $query->select(['order_on_way_road_id', 'shipment_driver_id', 'route', 'real_road']),
                'in_process_road' => fn(HasOneThrough $query) => $query->select(['order_in_process_road_id', 'shipment_driver_id', 'route', 'real_road']),
                'completed' => fn($query) => $query->select([
                    'completed_order_id',
                    'order_id',
                    'cost',
                    'distance',
                    'duration',
                    'duration_price',
                    'distance_price',
                    'destination_address',
                    'destination_lat',
                    'destination_lut',
                ]),
                'crossing' => fn($query) => $query->select([
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
                'corporate_clients' => fn($query) => $query
                    ->where('company_id', '=', $company_id)
                    ->select([
                        'corporate_client_id',
                        'corporate_clients.client_id',
                        'corporate_clients.company_id',
                        'corporate_clients.name',
                        'corporate_clients.surname',
                        'corporate_clients.patronymic'
                    ]),
                'corporate' => fn($query) => $query->select(['order_corporate_id', 'order_id', 'slip_number']),
                'driver' => fn($query) => $query
                    ->with(['driver_info', 'car'])
                    ->select(['drivers.driver_id', 'drivers.driver_info_id', 'drivers.car_id', 'drivers.phone', 'current_status_id']),
                'completed_driver' => fn($query) => $query
                    ->with(['driver_info', 'car'])
                    ->select(['drivers.driver_id', 'drivers.driver_info_id', 'drivers.car_id', 'drivers.phone']),
                'initial_data' => fn($query) => $query->select('order_initial_data_id', 'order_id', 'price'),
                'tariff' => fn($query) => $query->with('current_tariff')->select(['*'])
            ])
            ->orderBy('order_id', 'desc');

        $sum = $this->calcTotalCoast($company_id, $date_start, $date_end, $status_id, $order_type_id);
        $coast = collect(['sum' => $sum]);
        $paginate = $orders->paginate($per_page);

        return $coast->merge($paginate);
    }

    /**
     * @param $company_id
     * @param $date_start
     * @param $date_end
     * @param $status_id
     * @param $order_type_id
     * @return int|string
     */
    public function calcTotalCoast($company_id, $date_start, $date_end, $status_id, $order_type_id)
    {
        return $this->orderCompletedContract
            ->whereHas('corporate', fn(Builder $query) => $query->where('company_id', '=', $company_id))
            ->whereHas('order', fn(Builder $query) => $query
                ->when($date_start && $date_end, fn($query) => $query
                    ->where('created_at', '>=', $date_start)
                    ->where('created_at', '<=', $date_end))
                ->when($status_id, fn($query) => $query->where('status_id', '=', $status_id))
                ->when($order_type_id, fn($query) => $query->where('order_type_id', '=', $order_type_id))
            )
            ->sum('cost');
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function franchiseCompaniesPaginate($request): LengthAwarePaginator
    {
        $per_page = isset($request['per-page']) && $request['per-page'] ? $request['per-page'] : 10;
        $page = isset($request['page']) && $request['page'] ? $request['page'] : 1;
        $search = (isset($request['search']) && $request['search'] && 'null' !== $request['search']) ? $request['search'] : '';

        return $this->companyContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->when($search, fn($q) => $q->where(fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')->orWhere('email', 'LIKE', '%'.$search.'%')))
            ->with('tariffs')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $data
     * @param $id
     * @return bool|mixed
     * @throws Exception
     */
    public function adminUpdateCompany($id, $data)
    {
        return $this->companyContract->beginTransaction(function () use ($id, $data) {
            $this->companyContract->forgetCache();

            $company = $this->companyContract->update($id, $data);

            if (!$company) {
                return null;
            }

            if ($data['phones']) {
                $this->companyPhoneContract->where('company_id', '=', $id)->deletes();
                array_map(fn($phone) => $this->companyPhoneContract->create(['company_id' => $id, 'number' => $phone]), $data['phones']);
            }

            return true;
        });
    }

    /**
     * @param $company_id
     * @return mixed
     */
    public function deleteFranchiseCompany($company_id)
    {
        return $this->companyContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->delete($company_id);
    }

    /**
     * @param $phone
     * @return mixed
     */
    public function getFranchiseCompanyByPhone($phone)
    {
        return $this->companyContract
            ->whereHas('phones', fn($q) => $q->where('number', $phone))
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->findFirst();
    }

    /**
     * @param $phone
     * @param  array  $values
     * @return mixed
     */
    public function getCompanyByPhone($phone, array $values = [])
    {
        return $this->companyContract
            ->whereHas('phones', fn(Builder $query) => $query->where('number', '=', $phone))
            ->findFirst(['company_id'.implode(',', $values)]);
    }

    /**
     * @param $company_id
     * @return string|null
     */
    public function getPhoneMask($company_id): ?string
    {
        $company = $this->companyContract->where('company_id', '=', $company_id)
            ->with([
                'franchise' => fn($q) => $q->with('country', function ($query) {
                    $query->select('country_id', 'phone_mask');
                })->select('franchise_id', 'country_id')
            ])->findFirst();

        return $company['franchise']['country']['phone_mask'];
    }

    /**
     * @param  array  $coordinate
     * @param  int  $company_id
     * @return bool|null
     */
    public function hasCompanyIsCoordinate(array $coordinate, $company_id): ?bool
    {
        $region_id = session('order.from_region_id');

        if (!$region_id) {
            $geocode = $this->geoService->getRightGeocode($coordinate);
            $region = $this->regionContract->where('name', '=', $geocode['province'])->findFirst([$this->regionContract->getKeyName(), 'name']);
            $region_id = $region->region_id ?? null;
        }

        if (!$region_id) {
            return false;
        }

        return $this->companyContract
            ->where($this->companyContract->getKeyName(), '=', $company_id)
            ->whereHas(
                'tariffs',
                fn(Builder $tariff_query) => $tariff_query
                    ->whereJsonContains('tariffs.region->ids', $region_id)
                    ->select(['tariffs.tariff_id', 'tariffs.car_class_id', 'tariffs.region'])
            )
            ->exists();
    }

    /**
     * @inheritdoc
     */
    public function companyAttachTariff($company_id, $tariff_ids): bool|array
    {
        $has_company = $this->companyContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->where('company_id', '=', $company_id)
            ->exists();

        $ids = array_map(static fn($item) => ['company_id' => $company_id, 'tariff_id' => $item], $tariff_ids);

        if (!$has_company || !$this->companyContract->store($company_id, ['tariffs' => $ids], true)) {
            return false;
        }

        $company_tariffs = $this->companyContract
            ->with(['tariffs' => fn($query) => $query->select(['*'])])
            ->find($company_id);

        return $company_tariffs && $company_tariffs->tariffs ? $company_tariffs->tariffs->pluck('tariff_id')->toArray() : [];
    }

    /**
     * @param $client_phone
     * @param $company_id
     * @return void
     */
    public function closeOrderDialog($client_phone, $company_id): void
    {
        $client_service = $this->clientService;
        $initial_contract = $this->initialContract;

        $client = $client_service->getClientByPhone($client_phone);

        $initial = $initial_contract
            ->has('order')
            ->where('orderable_id', '=', $client->client_id)
            ->firstWhere(['orderable_type', '=', $client->getMap()]);

        if (!$initial) {
            $initial_contract
                ->where('orderable_id', '=', $client->client_id)
                ->where('orderable_type', '=', $client->getMap())
                ->deletes();
        }
    }

    /**
     * @inheritDoc
     */
    public function getCompanyCities($admin_id): ?object
    {
        return $this->adminCorporateContract->with('franchise_cities')->find($admin_id);
    }

    /**
     * @inheritDoc
     */
    public function getCities(): Collection
    {
        return $this->cityContract
            ->whereHas('country', fn(Builder $query) => $query->where('name', '=', 'Russia'))
            ->findAll(['city_id', 'name']);
    }

    /**
     * @param  int  $city_id
     * @return Collection|null
     */
    public function getAirports(int $city_id): ?Collection
    {
        return $this->airportContract
            ->whereHas('city', fn(Builder $query) => $query->where('city_id', '=', $city_id))
            ->findAll(['airport_id', 'city_id', 'name']);
    }

    /**
     * @param  int  $city_id
     * @return Collection|null
     */
    public function getStations(int $city_id): ?Collection
    {
        return $this->stationContract
            ->whereHas('city', fn(Builder $query) => $query->where('city_id', '=', $city_id))
            ->findAll(['railway_station_id', 'city_id', 'name']);
    }

    /**
     * @param  int  $city_id
     * @return Collection|null
     */
    public function getMetros(int $city_id): ?Collection
    {
        return $this->stationContract
            ->whereHas('city', fn(Builder $query) => $query->where('city_id', '=', $city_id))
            ->findAll(['railway_station_id', 'city_id', 'name']);
    }

    /**
     * @inheritDoc
     */
    public function printExcelData($request): array
    {
        $company_id = user()->company_id;

        $company = $this->companyContract->find($company_id, ['company_id', 'name']);
        $orders = $this->generateOrderExcel($request, false);

        return $this->excelCreate($orders, $company->company_name ?? 'clone', false);
    }

    /**
     * @inheritDoc
     */
    public function generateOrderExcel($request, bool $print = true): Collection
    {
        $company_id = user()->company_id;

        $dateStart = $request->date_start ?: false;
        $dateEnd = $request->date_end ? (new Carbon($request->date_end))->addDay()->format('Y-m-d') : false;
        $statusId = $request->status ?: false;
        $orderTypeId = $request->type ?: false;

        $orders = $this->orderContract
            ->whereHas('corporate', fn(Builder $query) => $query->where('company_id', '=', $company_id))
            ->where('payment_type_id', '=', PaymentType::getTypeId(PaymentType::COMPANY))
            ->when($dateStart, fn($query) => $query->where('created_at', '>=', $dateStart))
            ->when($dateEnd, fn($query) => $query->where('created_at', '<=', $dateEnd))
            ->when($statusId, fn($query) => $query->where('status_id', '=', $statusId))
            ->when($orderTypeId, fn($query) => $query->where('order_type_id', '=', $orderTypeId))
            ->with(
                [
                    'stage' => fn($query) => $query->select('order_stage_cord_id', 'order_id', 'accepted', 'started', 'ended'),
                    'client' => fn($query) => $query->select('client_id', 'name', 'surname', 'patronymic', 'phone'),
                    'passenger' => fn($query) => $query->select('client_id', 'name', 'surname', 'patronymic', 'phone'),
                    'status' => fn($query) => $query->select('order_status_id', 'status', 'text'),
                    'completed' => fn($query) => $query->select('completed_order_id', 'order_id', 'destination_address', 'cost'),
                    'car_options' => fn($query) => $query->select('car_option_id', 'option', 'name'),
                ]
            )
            ->findAll(
                ['order_id', 'address_from', 'address_to', 'client_id', 'passenger_id', 'car_option', 'created_at', 'status_id']
            );

        $company = $this->companyContract->find($company_id, ['company_id', 'name']);

        [$path, $name] = $this->excelCreate($orders, $company->name ?? 'clone');

        $this->companyReportContract->create(['company_id' => $company_id, 'excel' => $path.$name, 'path' => $path, 'name' => $name]);

        if ($print) {
            return $this->parseResult($instance, ['path', 'name'], [$path, $name]);
        }

        return $orders;
    }

    /**
     * @param $orders
     * @param  string  $company_name
     * @param  bool  $print
     * @return array
     */
    protected function excelCreate($orders, string $company_name, bool $print = true): array
    {
        $company_name = str_replace(' ', '', preg_replace('/[^\p{L}\p{N}\s]\s+/u', '', $company_name));
        $path = 'company'.DS.'orders'.DS;
        $name = Str::random().$company_name.'_company.xlsx';

        $excel_data = [
            [
                'НОМЕР ЗАКАЗА',
                'ВРЕМЯ ЗАКАЗА',
                'ВРЕМЯ ЗАВЕРШЕНИЯ ПОЕЗДКИ',
                'КОНТАКТНОЕ ЛИЦО',
                'ПАССАЖИР',
                'АДРЕС ПОДАЧИ',
                'МАРШРУТ ПОЕЗДКИ',
                'ДОП УСЛУГИ',
                'СТОИМОСТЬ',
                'СТАТУС',
            ]
        ];

        foreach ($orders as $order) {
            $subject = $order->car_options->flatten()->map(fn($option) => $option->name)[0] ?? '';
            $options = str_replace(['[', ']', '"'], '', $subject);

            $excel_data[] =
                [
                    'НОМЕР ЗАКАЗА' => $order->order_id,
                    'ВРЕМЯ ЗАКАЗА' => $order->stage->started ?? '' ? $order->stage->started->format('Y-m-d H:i:s') : '',
                    'ВРЕМЯ ЗАВЕРШЕНИЯ ПОЕЗДКИ' => $order->stage->ended ?? '' ? $order->stage->ended->format('Y-m-d H:i:s') : '',
                    'КОНТАКТНОЕ ЛИЦО' => $order->client->name.' '.$order->client->surname,
                    'ПАССАЖИР' => $order->client->name.' '.$order->client->surname,
                    'АДРЕС ПОДАЧИ' => $order->address_from,
                    'МАРШРУТ ПОЕЗДКИ' => $order->address_to ?? $order->completed->destination_address ?? '',
                    'ДОП УСЛУГИ' => $order->car_option['ids'] ? trans($options) : '',
                    'СТОИМОСТЬ' => $order->completed->cost ?? '?',
                    'СТАТУС' => trans($order->status->text)
                ];
        }

        !Storage::disk('public')->exists(str_replace_first('/storage', '', $path))
            ? Storage::disk('public')->makeDirectory('company'.DS.'orders')
            : null;

        GenerateXSLX::fromArray($excel_data)->saveAs(storage_path($path).$name);

        return $print ? [$path, $name] : $excel_data;
    }

    /**
     * @param $code
     * @param $franchise_id
     * @return Collection
     */
    public function findCompaniesByCode($code, $franchise_id): object
    {
        return $this->companyContract
            ->where('franchise_id', '=', $franchise_id)
            ->where('code', '=', $code)
            ->findFirst();
    }

    /**
     * @param $order_id
     * @param $client_id
     * @return bool
     */
    public function cancelOrder($order_id, $client_id): bool
    {
        return $this->orderEndService->companyOrderEnd($order_id, $client_id);
    }


    /**
     * @param $franchise_id
     * @return mixed|object|null
     */
    public function getCurrentPhoneMask($franchise_id): mixed
    {
        return $this->franchiseContract->where('franchise_id','=',$franchise_id)
            ->with(['country' => fn($q) => $q->select(['country_id','phone_mask'])])
            ->findFirst(['franchise_id','country_id']);
    }
}
