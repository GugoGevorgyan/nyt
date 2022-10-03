<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Driver;


use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JsonException;
use Src\Broadcasting\Broadcast\Driver\LockedInfo;
use Src\Core\Enums\ConstDriverType;
use Src\Core\Enums\ConstQueue;
use Src\Exceptions\Lexcept;
use Src\Http\Requests\Driver\UpdateProfileRequest;
use Src\Jobs\FindView\SearchOrderForDriver;
use Src\Models\Driver\Driver;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverAddress\DriverAddressContract;
use Src\Repositories\DriverCandidate\DriverCandidateContract;
use Src\Repositories\DriverCoordinate\DriverCoordinateContract;
use Src\Repositories\DriverLock\DriverLockContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Services\DriverContract\DriverContractServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\ServicesCrud\BaseCrud;

/**
 * Class DriverCrud
 * @package Src\ServicesCrud\Driver
 */
class DriverCrud extends BaseCrud implements DriverCrudContract
{
    /**
     * DriverCrud constructor.
     * @param  DriverContract  $driverContract
     * @param  GeocodeServiceContract  $geoService
     * @param  DriverCandidateContract  $candidateContract
     * @param  DriverContractServiceContract  $driverContractService
     * @param  FranchiseContract  $franchiseContract
     * @param  DriverAddressContract  $driverAddressContract
     * @param  DriverLockContract  $driverLockContract
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected GeocodeServiceContract $geoService,
        protected DriverCandidateContract $candidateContract,
        protected DriverContractServiceContract $driverContractService,
        protected FranchiseContract $franchiseContract,
        protected DriverAddressContract $driverAddressContract,
        protected DriverLockContract $driverLockContract,
        protected DriverCoordinateContract $driverCoordinateContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function addFavoriteAddress($driver_id, $address, $lat, $lut, $target): ?object
    {
        $create_address = $this->driverAddressContract
            ->create([
                'driver_id' => $driver_id,
                'target' => $target,
                'address' => $address,
                'lat' => $lat,
                'lut' => $lut
            ]);

        if (!$create_address) {
            return null;
        }

        return $create_address;
    }

    /**
     * @inheritDoc @todo danger fix
     */
    public function updateFranchise(int $driver_id, int $franchise_id): bool
    {
        $driver = $this->driverContract->update($driver_id, ['current_franchise_id' => $franchise_id]);

        if (!$driver) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDriver($driver_id): ?Driver
    {
        $driver = $this->driverContract->find($driver_id);

        if (!$driver) {
            return null;
        }

        return $driver;
    }


    /**
     * @param  UpdateProfileRequest  $request
     * @return Driver|mixed|null
     */
    public function updateProfile(UpdateProfileRequest $request): ?Driver
    {
        $driver = $this->driverContract->update(
            $request->driver_id,
            [
                'phone' => $request->phone ?? '',
                'email' => $request->email ?? '',
                'name' => $request->email ?? '',
                'surname' => $request->surname ?? '',
            ]
        );

        if (!$driver) {
            return null;
        }

        return $driver;
    }

    /**
     * @param $driver_id
     * @return bool|mixed
     */
    public function deleteDriver($driver_id): bool
    {
        return $this->driverContract->delete($driver_id) ?: false;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function selectAddress(int $driver_id, int $address_id, array $cords): ?string
    {
        $driver = $this->driverContract
            ->with([
                'addresses' => fn(HasMany $query) => $query
                    ->where('driver_address_id', '=', $address_id)
                    ->select(['driver_address_id', 'driver_id', 'active'])
            ])
            ->find($driver_id, ['driver_id']);

        $address = $driver->addresses->first();

        if (!$address) {
            return null;
        }

        $address_cord = ['lat' => $address->lat, 'lut' => $address->lut];

        if ($address->active) {
            $this->driverAddressContract->update($address->driver_address_id, ['active' => false]);

            return 'deactivate';
        }

        $geo = $this->geoService->roadCalculation($cords, $address_cord, null, true, 'm');

        $this->driverAddressContract->update($address->driver_address_id, [
            'active' => true,
            'target_road' => decode($geo['points']),
            'target_distance' => $geo['distance'],
            'target_duration' => $geo['duration']
        ]);

        SearchOrderForDriver::dispatch($driver_id, $address_cord, $geo['points'])->onQueue(ConstQueue::LONG()->getValue());

        return 'activate';
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function franchiseDriversPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per_page'] ?: 10;
        $page = $request['page'] ?: 1;
        $search = ($request['search'] && null != $request['search']) ? $request['search'] : '';
        $type = isset($request['type']) && null != $request['type'] && is_numeric($request['type']) ? $request['type'] : null;
        $park = isset($request['park']) && null != $request['park'] && is_numeric($request['park']) ? $request['park'] : null;
        $activity = isset($request['activity']) && null != $request['activity'] && is_numeric($request['activity']) ? (int)$request['activity'] : null;
        $contract = isset($request['contract']) && null != $request['contract'] && is_numeric($request['contract']) ? (int)$request['contract'] : null;

        $searchWords = explode(' ', $search);

        return $this->driverContract
            ->where('drivers.current_franchise_id', '=', FRANCHISE_ID)
            ->when($type, fn($query) => $query->whereHas('active_contract', fn($q) => $q->where('driver_type_id', '=', $type)))
            ->when($contract && !$type, fn($query) => $query->whereHas('active_contract'))
            ->when(0 === $contract, fn($query) => $query->doesntHave('active_contract'))
            ->when($activity || 0 === $activity, fn($query) => $query->where('online', $activity))
            ->when($park, fn($query) => $query->whereHas('park', fn($q) => $q->where('parks.park_id', $park)))
            ->when($search, function ($query) use ($search, $searchWords) {
                $query->where(
                    function ($q) use ($search, $searchWords) {
                        return $q->where('nickname', 'LIKE', '%'.$search.'%')
                            ->orWhereHas(
                                'driver_info',
                                function ($q) use ($search, $searchWords) {
                                    $q->where('name', 'LIKE', '%'.$search.'%')
                                        ->orWhere('surname', 'LIKE', '%'.$search.'%')
                                        ->orWhere('patronymic', 'LIKE', '%'.$search.'%');

                                    foreach ($searchWords as $word) {
                                        $q->orWhere('name', 'LIKE', '%'.$word.'%')
                                            ->orWhere('surname', 'LIKE', '%'.$word.'%')
                                            ->orWhere('patronymic', 'LIKE', '%'.$word.'%');
                                    }
                                }
                            );
                    }
                );
            })
            ->with([
                'car' => fn($query) => $query->select([
                    'cars.car_id',
                    'park_id',
                    'entity_id',
                    'class',
                    'current_driver_id',
                    'franchise_id',
                    'body_number',
                    'vehicle_licence_number',
                    'vehicle_licence_date',
                    'sts_number',
                    'pts_number',
                    'sts_file',
                    'pts_file',
                    'registration_date',
                    'option',
                    'mark',
                    'model',
                    'year',
                    'images',
                    'status_id',
                    'rating',
                    'inspection_date',
                    'inspection_expiration_date',
                    'inspection_scan',
                    'insurance_date',
                    'insurance_expiration_date',
                    'insurance_scan',
                    'speedometer',
                    'state_license_plate',
                    'garage_number',
                    'vin_code',
                    'color'
                ]),
                'park' => fn($query) => $query->select([
                    'parks.park_id',
                    'parks.manager_id',
                    'parks.franchise_id',
                    'parks.name',
                    'parks.city_id',
                    'parks.address',
                    'parks.entity_id'
                ]),
                'driver_info' => fn($query) => $query->select([
                    'drivers_info.driver_info_id',
                    'name',
                    'surname',
                    'patronymic',
                    'email',
                    'license_qr_code',
                    'license_code',
                    'license_scan',
                    'license_date',
                    'license_expiry',
                    'passport_serial',
                    'passport_scan',
                    'photo',
                    'experience',
                    'birthday',
                    'passport_number',
                    'passport_issued_by',
                    'passport_when_issued',
                    'citizen',
                    'address',
                    'deposit',
                ]),
                'type' => fn($query) => $query->select([
                    'driver_types.driver_type_id',
                    'driver_types.type',
                    'driver_types.name',
                    'driver_types.description',
                    'driver_types.worked',
                ]),
                'subtype' => fn($query) => $query->select([
                    'driver_subtypes.driver_subtype_id',
                    'driver_subtypes.driver_type_id',
                    'driver_subtypes.name',
                    'driver_subtypes.value'
                ]),
                'graphic' => fn($query) => $query->select([
                    'driver_graphics.driver_graphic_id',
                    'driver_graphics.name',
                    'driver_graphics.type',
                    'driver_graphics.description',
                    'driver_graphics.working_days_count',
                    'driver_graphics.weekend_days_count'
                ]),
                'lockes' => fn($query) => $query->select(['driver_id', 'locked', 'lock_count', 'start', 'end'])
            ])
            ->orderBy('driver_id', 'desc')
            ->paginate($per_page, [
                'driver_id',
                'entity_id',
                'driver_info_id',
                'current_status_id',
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
                'current_franchise_id',
                'created_at'
            ], 'page', $page);
    }

    /**
     * @inheritdoc
     */
    public function blockDriver($driver_id, $minute)
    {
        $driver_lock = $this->driverLockContract
            ->where('driver_id', '=', $driver_id)
            ->where('locked', '=', true)
            ->findFirst(['driver_id', 'locked', 'start']);

        $start = $driver_lock->start ?? now();

        $this->driverLockContract->updateOrCreate(['driver_id', '=', $driver_id], [
            'driver_id' => $driver_id,
            'locked' => true,
            'lock_count' => DB::raw('lock_count+1'),
            'start' => $start,
            'end' => now()->addMinutes($minute),
        ]);

        $driver = $this->driverContract
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['driver_id', 'phone', 'current_franchise_id', 'car_id']);

        LockedInfo::broadcast($driver, [trans('messages.driver_locked_up'), 'locked' => true, 'time' => $minute]);

        return $this->driverLockContract
            ->where('driver_id', '=', $driver_id)
            ->where('locked', '=', true)
            ->findFirst(['driver_id', 'locked', 'start', 'end']);
    }

    /**
     * @inheritdoc
     */
    public function unBlockDriver($driver_id)
    {
        $this->driverLockContract
            ->where('driver_id', '=', $driver_id)
            ->where('locked', '=', 1)
            ->updateSet(['locked' => 0]);

        $driver = $this->driverContract
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['driver_id', 'phone', 'current_franchise_id', 'car_id']);

        LockedInfo::broadcast($driver, [trans('messages.driver_locked_dawn'), 'locked' => false, 'time' => 0]);

        return true;
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function callCenterDriversPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] && is_numeric($request['per-page']) ? $request['per-page'] : '25';
        $page = $request->page && is_numeric($request->page) ?: 1;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : '';

        return $this->driverContract
            ->where('current_franchise_id', '=', FRANCHISE_ID)
            ->where('online', '=', 1)
            ->where(
                fn($q) => $q->whereHas(
                    'driver_info', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('surname', 'LIKE', '%'.$search.'%')
                    ->orWhere('patronymic', 'LIKE', '%'.$search.'%'))
                    ->orWhereHas('status', fn($q) => $q->where('text', 'LIKE', '%'.$search.'%'))
                    ->orWhereHas(
                        'car',
                        fn($q) => $q->where('state_license_plate', 'LIKE', '%'.$search.'%')
                            ->orWhere('garage_number', 'LIKE', '%'.$search.'%')
                            ->orWhere('mark', 'LIKE', '%'.$search.'%')
                            ->orWhere('model', 'LIKE', '%'.$search.'%')
                            ->orWhereHas('classes', fn($q) => $q->where('class_name', 'LIKE', '%'.$search.'%'))
                            ->orWhereHas('park', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                            )
                    )
            )
            ->with(['status', 'driver_info', 'active_order_shipment.status', 'car.classes', 'car.park'])
            ->orderBy('driver_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @return array|Collection
     */
    public function callCenterGetDrivers(): array|Collection
    {
        return $this->driverContract
            ->where('current_franchise_id', '=', FRANCHISE_ID)
            ->where('online', '=', true)
            ->where('is_ready', '=', true)
            ->with([
                'status' => fn($query) => $query->select(['driver_status_id', 'name', 'status', 'text', 'color']),
                'driver_info' => fn($query) => $query->select(['driver_info_id', 'franchise_id', 'name', 'surname', 'patronymic', 'email', 'photo']),
                'active_order_shipment.status',
                'car.classes',
                'car.park'
            ])
            ->whereHas('status')
            ->findAll([
                'driver_id',
                'driver_info_id',
                'entity_id',
                'car_id',
                'current_franchise_id',
                'current_status_id',
                'lat',
                'lut',
                'azimuth',
                'logged',
                'online',
                'is_ready',
                'phone',
            ])
            ?: [];
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function candidateCreateDriver($request)
    {
        /*get candidate*/
        $candidate = $this->candidateContract->find($request->candidate_id);

        if (!$candidate && $candidate->learn_end && $candidate->learn_end > date('Y-m-d')) {
            return false;
        }

        /*restore/make driver*/
        $phone = $request->phone ?? $candidate->phone;

        $this->driverContract->restore((int)$request['driver_id']);

        $rating = $this->defaultRatingByCreate($request->type_id);

        $driver_data = [
            'driver_id' => $request['driver_id'],
            'current_franchise_id' => FRANCHISE_ID,
            'entity_id' => $request->entity_id ?: null,
            'driver_info_id' => $candidate->driver_info_id,
            'nickname' => $request->nickname,
            'password' => $request->password,
            'phone' => $phone,
            'selected_class' => ['ids' => []],
            'rating' => $rating['rating'],
            'mean_assessment' => $rating['assessment']
        ];

        return $this->candidateContract->beginTransaction(function () use ($driver_data, $candidate, $request) {
            $this->candidateContract->forgetCache();

            if (!$this->driverContract->updateOrCreate(['driver_id', '=', $request['driver_id']], $driver_data)) {
                return false;
            }

            if (!$driver_data['driver_id']) {
                $driver = $this->driverContract
                    ->whereHas('candidate', fn(Builder $query) => $query->where('driver_candidate_id', '=', $candidate->driver_candidate_id))
                    ->findFirst(['driver_id']);
            }

            $contract_data = [
                'driver_id' => $driver_data['driver_id'] ?? $driver->driver_id,
                'type_id' => $request->type_id,
                'subtype_id' => $request->subtype_id,
                'graphic_id' => $request->graphic_id,
                'free_days_price' => $request->free_days_price,
                'busy_days_price' => $request->busy_days_price,
                'car_id' => null,
                'entity_id' => null,
                'signing_day' => now()->format('Y-m-d'),
                'expiration_day' => $request->expiration_day,
                'work_start_day' => $request->work_start_day,
            ];

            return !(!$this->driverContractService->createUnsignedContract($contract_data)
                || !$this->candidateContract
                    ->where('driver_candidate_id', '=', $candidate->driver_candidate_id)
                    ->updateSet(['learn_status' => 3]));
        });
    }

    /**
     * @param $type
     * @return array
     * @throws Lexcept
     */
    protected function defaultRatingByCreate($type): array
    {
        $rating = 0;
        $assessment = 0.0;

        $franchise = $this->franchiseContract
            ->with([
                'option' => fn($query) => $query->select([
                    'franchise_option_id',
                    'franchise_id',
                    'default_rating',
                    'default_assessment'
                ])
            ])
            ->find(FRANCHISE_ID, ['franchise_id']);

        if (!$franchise && !$franchise->option) {
            throw new Lexcept("'Error Franchise doesn't have options", 422);
        }

        switch ($type) {
            case ConstDriverType::AGGREGATOR();
                $rating = $franchise->option['default_rating'][ConstDriverType::AGGREGATOR()->getValue()];
                $assessment = $franchise->option['default_assessment'][ConstDriverType::AGGREGATOR()->getValue()];
                break;

            case ConstDriverType::CORPORATE();
                $rating = $franchise->option['default_rating'][ConstDriverType::CORPORATE()->getValue()];
                $assessment = $franchise->option['default_assessment'][ConstDriverType::CORPORATE()->getValue()];
                break;

            case ConstDriverType::ROLL();
                $rating = $franchise->option['default_rating'][ConstDriverType::ROLL()->getValue()];
                $assessment = $franchise->option['default_assessment'][ConstDriverType::ROLL()->getValue()];
                break;

            case ConstDriverType::TENANT();
                $rating = $franchise->option['default_rating'][ConstDriverType::TENANT()->getValue()];
                $assessment = $franchise->option['default_assessment'][ConstDriverType::TENANT()->getValue()];
                break;
            default:
        }

        return compact('rating', 'assessment');
    }

    /**
     * @inheritdoc
     */
    public function getTrajectoryByDate(int $driver_id, string $date): array
    {
        $cords = $this->driverCoordinateContract
                ->where('driver_id', '=', $driver_id)
                ->where('date', '=', $date)
                ->findFirst(['coordinates'])
                ->coordinates ?? [];

        if (!$cords) {
            return $cords;
        }

        $distance = 0;

        foreach ($cords as $key => $cord) {
            $prev = $cords[$key - 1] ?? null;

            $distance += $prev ? distance_cords($prev['lat'], $prev['lut'], $cord['lat'], $cord['lut']) : 0;
        }

        $cords['distance'] = $distance;

        return $cords;
    }
}
