<?php

declare(strict_types=1);


namespace Src\Services\ParkManagement;

use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Repositories\Car\CarContract;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Park\ParkContract;

/**
 * Class ParkManagement
 * @package Src\Services\ParkManagement
 */
class ParkManagementService extends BaseService implements ParkManagementServiceContract
{
    /**
     * @var CarContract
     */
    protected CarContract $carContract;
    /**
     * @var CarClassContract
     */
    protected CarClassContract $carClassContract;
    /**
     * @var ParkContract
     */
    protected ParkContract $parkContract;
    /**
     * @var DriverContract
     */
    protected DriverContract $driverContract;

    /**
     * ParkManagementService constructor.
     * @param  CarContract  $carContract
     * @param  CarClassContract  $carClassContract
     * @param  ParkContract  $parkContract
     * @param  DriverContract  $driverContract
     */
    public function __construct(
        CarContract $carContract,
        CarClassContract $carClassContract,
        ParkContract $parkContract,
        DriverContract $driverContract
    ) {
        $this->carContract = $carContract;
        $this->carClassContract = $carClassContract;
        $this->parkContract = $parkContract;
        $this->driverContract = $driverContract;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function carsPaginate($request)
    {
        $per_page = isset($request['per_page']) && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = isset($request->page) && is_numeric($request->page) ? $request->page : 1;

        $search_attribute = isset($request->search_attribute) && null != $request->search_attribute ? $request->search_attribute : null;
        $search = isset($request->search) && null != $request->search ? $request->search : null;

        $filter_class = isset($request->filter_class) && null != $request->filter_class ? $request->filter_class : null;
        $filter_status = isset($request->filter_status) && null != $request->filter_status ? $request->filter_status : null;

        $cars = $this->carContract
            ->withCount('crashes')
            ->with([
                'status',
                'park',
                'classes',
                'current_driver.driver_info',
                'drivers' => fn($q) => $q->withCount('crashes')->withCount('contracts')
                    ->with([
                        'driver_info',
                        'active_contract.type',
                        'active_contract.subtype',
                        'crashes' => fn($q) => $q->where('our_fault', '=', 1)
                    ])
            ])
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->whereHas('park', fn($q) => $q->where('manager_id', '=', USER_ID))
            ->when($search, function ($q) use ($search_attribute, $search) {
                if ($search_attribute) {
                    $search_attribute_segments = explode('.', $search_attribute);

                    if (!empty($search_attribute_segments[2])) {
                        $q->whereHas($search_attribute_segments[0],
                            fn($q) => $q->whereHas($search_attribute_segments[1], fn($q) => $q->where($search_attribute_segments[2], 'LIKE', '%'.$search.'%')));
                    } else {
                        if (!empty($search_attribute_segments[1])) {
                            $q->whereHas($search_attribute_segments[0], fn($q) => $q->where($search_attribute_segments[1], 'LIKE', '%'.$search.'%'));
                        } else {
                            $q->where($search_attribute, 'LIKE', '%'.$search.'%');
                        }
                    }
                } else {
                    $q->where(fn($q) => $q->where('garage_number', 'LIKE', '%'.$search.'%')
                        ->orWhere('mark', 'LIKE', '%'.$search.'%')
                        ->orWhere('model', 'LIKE', '%'.$search.'%')
                        ->orWhere('year', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('drivers', fn($q) => $q->whereHas('driver_info', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')))
                        ->orWhereHas('park', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%'))
                    );
                }
            })
            ->when($filter_class, fn($q) => $q->whereHas('classes', fn($q) => $q->where('car_class_id', '=', $filter_class)))
            ->when($filter_status, fn($q) => $q->whereHas('status', fn($q) => $q->where('car_status_id', '=', $filter_status)))
            ->paginate($per_page, ['*'], 'page', $page);

        $cars->map(function ($car) {
            $car['inspection_days_left'] = $car['inspection_expiration_date'] ? $this->leftDays($car['inspection_expiration_date']) : '';
            $car['insurance_days_left'] = $car['inspection_expiration_date'] ? $this->leftDays($car['insurance_expiration_date']) : '';
            return $car;
        });

        return $cars;
    }

    /**
     * @param $end
     * @return float
     */
    protected function leftDays($end): float
    {
        $time = strtotime($end) - time();
        return round($time / (60 * 60 * 24));
    }

    /**
     * @param $request
     * @return mixed
     */
    public function updateDriverCar($request)
    {
        $driver = $this->driverContract->find($request->driver_id, ['driver_id']);

        if (!$driver) {
            return false;
        }

        $this->driverContract->update($driver->driver_id, ['car_id' => $request->car_id]);
    }

    /**
     * @inheritDoc
     */
    public function getParks(array $values = ['*'])
    {
        return $this->parkContract->where('franchise_id', '=', FRANCHISE_ID)->findAll($values);
    }

    /**
     * @param $search
     * @return Collection
     */
    public function getFreeDrivers($search): Collection
    {
        return $this->driverContract
            ->where('current_franchise_id', '=', FRANCHISE_ID)
            ->where('car_id', '=', null)
            ->whereHas('driver_info', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('surname', 'LIKE', '%'.$search.'%')
            )
            ->whereHas('unsigned_contract')
            ->with([
                'driver_info',
                'unsigned_contract.type',
                'unsigned_contract.subtype',
                'crashes' => fn($q) => $q->where('our_fault', '=', 1)
            ])
            ->withCount('crashes')
            ->withCount('contracts')
            ->findAll();
    }

    /**
     * @param $request
     * @return bool|\Illuminate\Database\Eloquent\Collection|null
     */
    public function updateStatus($request)
    {
        $car = $this->carContract->where('franchise_id', '=', FRANCHISE_ID)->find($request->car_id);

        if (!$car) {
            return false;
        }

        return $this->carContract->where('car_id', '=', $request->car_id)->updateSet(['status_id' => $request->status_id]);
    }
}
