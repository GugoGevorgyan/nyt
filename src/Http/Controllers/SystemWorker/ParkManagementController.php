<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\UpdateParkManagementStatusRequest;
use Src\Services\Car\CarServiceContract;
use Src\Services\Driver\DriverService;
use Src\Services\DriverContract\DriverContractServiceContract;
use Src\Services\ParkManagement\ParkManagementServiceContract;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ParkManagementController
 * @package Src\Http\Controllers\SystemWorker
 */
class ParkManagementController extends Controller
{
    /**
     * @param ParkManagementServiceContract $baseService
     * @param CarServiceContract $carServiceContract
     * @param DriverContractServiceContract $driverContractService
     * @param DriverService $driverService
     */
    public function __construct(
        protected ParkManagementServiceContract $baseService,
        protected CarServiceContract $carServiceContract,
        protected DriverContractServiceContract $driverContractService,
        protected DriverService $driverService
    ) {}

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View
     * @throws JsonException
     */
    public function index(): \Illuminate\Contracts\View\View|Factory|Application
    {
        $statuses = json_encode($this->carServiceContract->getCarStatuses(), JSON_THROW_ON_ERROR);
        $parks = json_encode($this->baseService->getParks(), JSON_THROW_ON_ERROR);
        $classes = json_encode($this->carServiceContract->getCarClasses(), JSON_THROW_ON_ERROR);

        return view('system-worker.park-management.index', compact('statuses', 'parks', 'classes'));
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function paginate(Request $request): Response|Application|ResponseFactory
    {
        $cars = $this->baseService->carsPaginate($request);

        return response($cars);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function freeDrivers(Request $request): Response|Application|ResponseFactory
    {
        return response($this->baseService->getFreeDrivers($request['search']));
    }

    /**
     * @param  UpdateParkManagementStatusRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function statusUpdate(UpdateParkManagementStatusRequest $request): Response|Application|ResponseFactory
    {
        return $this->baseService->updateStatus($request)
            ? response(['message' => trans('messages.car_status_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }


    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function printContract(Request $request): Response|Application|ResponseFactory
    {
        $data = $this->driverContractService->createContractFile($request);

        return $data
            ? response($data)
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function signContract(Request $request): Response|Application|ResponseFactory
    {
        return $this->driverContractService->signContract($request->contract_id)
            ? response(['message' => trans('messages.contract_signed')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  int  $contract_id
     * @return Application|ResponseFactory|Response|BinaryFileResponse
     */
    public function downloadContract(int $contract_id): Response|BinaryFileResponse|Application|ResponseFactory
    {
        $contract_scan = $this->driverContractService->downloadContract($contract_id);

        if (!\Storage::disk('local')->exists(str_replace('storage', '', $contract_scan))) {
            return response(['message' => 'error'], 500);
        }

        return \Response::download(storage_path(str_replace('storage', 'app', $contract_scan)));
    }


    /**
     * @param $driver_id
     * @param $car_id
     * @return Application|ResponseFactory|Response
     * @throws \Exception
     */
    public function removeDriver($driver_id, $car_id)
    {
       $result = $this->driverService->removeDriverOnCar($driver_id, $car_id);

       if (!$result) {
           return response(['message' => trans('messages.something_went_wrong')], 500);
       }

       return response(['message' => 'Водитель отсоединен от автомобиля'], 200);
    }
//    /**
//     * @param UpdateDriverCarRequest $request
//     * @return Application|ResponseFactory|Response
//     */
//    public function updateDriverCar(UpdateDriverCarRequest $request)
//    {
//        return $this->baseService->updateDriverCar($request)?
//            response(['message' => trans('messages.driver_added_to_car')], 200):
//            response(['message' => trans('messages.something_went_wrong')], 500);
//    }
}
