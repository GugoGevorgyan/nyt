<?php

declare(strict_types=1);


namespace Src\Services\TrafficSafety;

use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ServiceEntity\BaseService;
use Src\Models\Driver\DriverInfo;
use Src\Repositories\Car\CarContract;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\CarCrash\CarCrashContract;
use Src\Repositories\CarCrashImage\CarCrashImageContract;
use Src\Repositories\CarStatus\CarStatusContract;
use Src\Repositories\Park\ParkContract;
use Src\Services\Payment\PaymentServiceContract;

use function is_string;

/**
 * Class TrafficSafety
 * @package Src\Services\TrafficSafety
 */
class TrafficSafetyService extends BaseService implements TrafficSafetyServiceContract
{
    /**
     * TrafficSafetyService constructor.
     * @param  CarContract  $carContract
     * @param  CarClassContract  $carClassContract
     * @param  CarCrashContract  $carCrashContract
     * @param  CarCrashImageContract  $carCrashImageContract
     * @param  ParkContract  $parkContract
     * @param  CarStatusContract  $carStatusContract
     * @param  PaymentServiceContract  $paymentService
     */
    public function __construct(
        protected CarContract $carContract,
        protected CarClassContract $carClassContract,
        protected CarCrashContract $carCrashContract,
        protected CarCrashImageContract $carCrashImageContract,
        protected ParkContract $parkContract,
        protected CarStatusContract $carStatusContract,
        protected PaymentServiceContract $paymentService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function carsPaginate($request): LengthAwarePaginator
    {
        $per_page = isset($request['per_page']) && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = isset($request->page) && is_numeric($request->page) ? $request->page : 1;
        $search = isset($request->search) && $request->search ? $request->search : null;

        $info_fillable = (new DriverInfo())->getFillable();
        array_unshift($info_fillable, 'drivers_info.driver_info_id');

        $cars = $this->carContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->when($search, fn($q) => $q
                ->where('state_license_plate', 'LIKE', '%'.$search.'%')
                ->orWhere('model', 'LIKE', '%'.$search.'%')
                ->orWhere('mark', 'LIKE', '%'.$search.'%')
                ->orWhereHas('status', fn($q) => $q->where('text', 'LIKE', '%'.$search.'%'))
                ->orWhereHas('classes', fn($q) => $q->where('class_name', 'LIKE', '%'.$search.'%'))
                ->orWhereHas('park', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('drivers', fn($q) => $q->where('nickname', 'LIKE', '%'.$search.'%'))
                    ->orderBy('car_id', 'desc')),
            )
            ->with([
                'status' => fn($query) => $query->select(['car_status_id', 'value', 'text', 'color', 'description']),
                'park' => fn($query) => $query->select(['park_id', 'manager_id', 'entity_id', 'name', 'address', 'image']),
                'classes' => fn($query) => $query->select(['car_class_id', 'class_name', 'description', 'image']),
                'drivers.driver_info' => fn($query) => $query->select($info_fillable),
                'current_driver_info' => fn($query) => $query->select($info_fillable),
            ])
            ->withCount(['crashes'])
            ->orderBy('created_at', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        $cars->map(
            function ($car) {
                $car['inspection_days_left'] = $car['inspection_expiration_date'] ? $this->leftDays($car['inspection_expiration_date']) : null;
                $car['insurance_days_left'] = $car['insurance_expiration_date'] ? $this->leftDays($car['insurance_expiration_date']) : null;
                return $car;
            }
        );

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
     * @param $car_id
     * @return mixed
     */
    public function getFranchiseCar($car_id)
    {
        return $this->carContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->find($car_id);
    }

    /**
     * @return Collection
     */
    public function getParks(): Collection
    {
        return $this->parkContract->where('franchise_id', '=', FRANCHISE_ID)->findAll();
    }

    /**
     * @param $car
     * @param $page
     * @param $per_page
     * @return LengthAwarePaginator
     */
    public function getCrashes($car, $page, $per_page): LengthAwarePaginator
    {
        return $this->carCrashContract
            ->with(['driver.driver_info', 'images'])
            ->where('car_id', '=', $car)
            ->orderBy('dateTime', 'desc')
            ->paginate($per_page, ['*'], 'page', $page)
            ->map(fn($crash) => $crash->dateTime = date('d M Y H:i', strtotime($crash->dateTime)));
    }

    /**
     * @return Collection|mixed
     */
    public function getCarClasses()
    {
        return $this->carClassContract->findAll();
    }

    /**
     * @return array|mixed
     */
    public function getStatuses()
    {
        return $this->carStatusContract->findAll();
    }

    /**
     * @inheritDoc
     */
    public function createCar(array $car_data): ?object
    {
        $prepare_data = $this->prepareCarData($car_data);

        $car = $prepare_data ? $this->carContract->create($prepare_data) : null;

        if (!$car) {
            return null;
        }

        return $this->carContract->find($car->{$car->getKeyName()});
    }

    /**
     * @param  array  $car_data
     * @return array|null
     */
    protected function prepareCarData(array $car_data): ?array
    {
        if (isset($car_data['sts_file'])) {
            if (is_string($car_data['sts_file'])) {
                $car_data['sts_file'] = $car_data['sts_file'];
            } elseif ($car_data['sts_file'][0]) {
                $car_data['sts_file'] = $this->createStsPdf($car_data['sts_file']);
            }
        }

        if (isset($car_data['pts_file'])) {
            if (is_string($car_data['pts_file'])) {
                $car_data['pts_file'] = $car_data['pts_file'];
            } elseif ($car_data['pts_file'][0]) {
                $car_data['pts_file'] = $this->createStsPdf($car_data['pts_file']);
            }
        }

        $car_data['franchise_id'] = FRANCHISE_ID;
        $car_data['class'] = ['ids' => array_map('\intval', $car_data['class_ids'])];
        unset($car_data['class_ids']);

        if (isset($car_data['images']) && $car_data['images']) {
            $path = storage_path('public'.DS.'cars'.DS);
            $web_path = '/storage/cars/';

            foreach ($car_data['images'] as $image) {
                $name[] = $web_path.$this->fileUpload($image, $path);
            }

            $car_data['images'] = $name ?? null;
        }

        if ($car_data['insurance_scan_file']) {
            $car_data['insurance_scan'] = $this->saveScan($car_data['insurance_scan_file'], 'insurance');

            if (!$car_data['insurance_scan']) {
                return null;
            }
        }

        if ($car_data['inspection_scan_file']) {
            $car_data['inspection_scan'] = $this->saveScan($car_data['inspection_scan_file'], 'inspection');

            if (!$car_data['inspection_scan']) {
                return null;
            }
        }

        return $car_data;
    }

    /**
     * @param  array  $files
     * @return string|null
     */
    protected function createStsPdf(array $files): ?string
    {
        if (!$files) {
            return null;
        }

        $path = storage_path('public'.DS.'cars'.DS);
        $web_path = '/storage/cars/';

        foreach ($files as $file) {
            $files[] = $this->fileUpload($file, $path, [700, 500]);
        }

        $pdf = PDF::loadView('system-worker.driver-contract-files.image-insert', ['images' => $files, 'path' => $path]);
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf_name = Str::random().'.pdf';
        $pdf->save(storage_path('cars'.DS.$pdf_name));

        foreach ($files as $file) {
            $this->deleteOldFile('cars'.DS.$file);
        }

        return $web_path.$pdf_name;
    }

    /**
     * @param $file
     * @param $folder
     * @return false|string|null
     */
    protected function saveScan($file, $folder)
    {
        try {
            $path = storage_path('public'.DS.'traffic-safety'.DS.$folder);
            $uploaded = $this->fileUpload($file, $path);

            return $uploaded ? '/storage/traffic-safety/'.$folder.'/'.$uploaded : false;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function updateCar(int $car_id, array $request)
    {
        $prepare_data = $this->prepareCarData($request);

        if (!$prepare_data) {
            return null;
        }

        return $this->carContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->where('car_id', '=', $car_id)
            ->updateSet($prepare_data);
    }

    /**
     * @inheritDoc
     */
    public function createCrash($data)
    {
        try {
            $crash = $this->carCrashContract->create($data);

            $path = storage_path('public'.DS.'traffic-safety'.DS.'crashes'.DS);

            if (!$crash) {
                return false;
            }

            foreach ($data['images'] as $i_value) {
                $image = $this->fileUpload($i_value, $path);
                $this->carCrashImageContract->create(['car_crash_id' => $crash->car_crash_id, 'name' => '/storage/traffic-safety/crashes/'.$image]);
            }

            $this->paymentService->carCrashCostDistributor($data['driver_id'], $data['act_sum'], $data['our_fault'], $crash);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $crash
     * @return bool|object
     */
    public function deleteCrash($crash): object|bool
    {
        return $this->carCrashContract->delete($crash);
    }

    /**
     * @param $request
     * @return bool|\Illuminate\Database\Eloquent\Collection
     */
    public function updatePark($request)
    {
        if (!$this->carContract->where('franchise_id', '=', FRANCHISE_ID)->where('car_id', '=', $request->car_id)->exists()) {
            return false;
        }

        return $this->carContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->where('car_id', '=', $request->car_id)
            ->updateSet(['park_id' => $request->park_id]);
    }

    /**
     * @param $request
     * @return bool|\Illuminate\Database\Eloquent\Collection
     */
    public function updateStatus($request)
    {
        if (!$this->carContract->where('franchise_id', '=', FRANCHISE_ID)->where('car_id', '=', $request->car_id)->exists()) {
            return false;
        }

        return $this->carContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->where('car_id', '=', $request->car_id)
            ->updateSet(['status_id' => $request->status_id]);
    }

    /**
     * @inheritDoc
     */
    public function updateInspection($request): bool
    {
        if (!$this->carContract->where('franchise_id', '=', FRANCHISE_ID)->where('car_id', '=', $request->car_id)->exists()) {
            return false;
        }

        try {
            $data = [
                'inspection_date' => $request->inspection_date,
                'inspection_expiration_date' => $request->inspection_expiration_date
            ];

            if ($request->hasFile('inspection_scan_file')) {
                $data['inspection_scan'] = $this->saveScan($request->inspection_scan_file, 'inspection');

                if (!$data['inspection_scan']) {
                    return false;
                }
            }

            $this->carContract
                ->where('franchise_id', '=', FRANCHISE_ID)
                ->where('car_id', '=', $request->car_id)
                ->updateSet($data);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $request
     * @return bool
     */
    public function updateInsurance($request): bool
    {
        if (!$this->carContract->where('franchise_id', '=', FRANCHISE_ID)->where('car_id', '=', $request->car_id)->exists()) {
            return false;
        }

        try {
            $data = [
                'insurance_date' => $request->insurance_date,
                'insurance_expiration_date' => $request->insurance_expiration_date
            ];

            if ($request->hasFile('insurance_scan_file')) {
                $data['insurance_scan'] = $this->saveScan($request->insurance_scan_file, 'insurance');
                if (!$data['insurance_scan']) {
                    return false;
                }
            }

            $this->carContract
                ->where('franchise_id', '=', FRANCHISE_ID)
                ->where('car_id', '=', $request->car_id)
                ->updateSet($data);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function downloadStsPts(int $car_id, string $type): string
    {
        $file_name = $type.'_file';
        return $this->carContract->find($car_id, ['car_id', $file_name])->{$file_name};
    }
}
