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
use Src\Http\Requests\SystemWorker\CreateCrashRequest;
use Src\Http\Requests\SystemWorker\CreateTrafficSafetyRequest;
use Src\Http\Requests\SystemWorker\UpdateTrafficSafetyInspectionRequest;
use Src\Http\Requests\SystemWorker\UpdateTrafficSafetyInsuranceRequest;
use Src\Http\Requests\SystemWorker\UpdateTrafficSafetyParkRequest;
use Src\Http\Requests\SystemWorker\UpdateTrafficSafetyRequest;
use Src\Http\Requests\SystemWorker\UpdateTrafficSafetyStatusRequest;
use Src\Services\LegalEntity\LegalEntityServiceContract;
use Src\Services\TrafficSafety\TrafficSafetyServiceContract;
use Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


/**
 * Class TrafficSafetyController
 * @package Src\Http\Controllers\SystemWorker
 */
class TrafficSafetyController extends Controller
{
    /**
     * @var TrafficSafetyServiceContract
     */
    protected TrafficSafetyServiceContract $baseService;
    /**
     * @var LegalEntityServiceContract
     */
    protected LegalEntityServiceContract $entityServiceContract;

    /**
     * TrafficSafetyController constructor.
     * @param  TrafficSafetyServiceContract  $baseService
     * @param  LegalEntityServiceContract  $entityServiceContract
     */
    public function __construct(
        TrafficSafetyServiceContract $baseService,
        LegalEntityServiceContract $entityServiceContract
    ) {
        $this->baseService = $baseService;
        $this->entityServiceContract = $entityServiceContract;
    }

    /**
     * @return Application|Factory|View
     * @throws JsonException
     */
    public function index()
    {
        $statuses = json_encode($this->baseService->getStatuses(), JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK);
        $parks = json_encode($this->baseService->getParks(), JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK);

        return view('system-worker.traffic-safety.index', compact('statuses', 'parks'));
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function paginate(Request $request)
    {
        return response(json_encode($this->baseService->carsPaginate($request), JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK));
    }

    /**
     * @param $car
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function getCrashes($car, Request $request)
    {
        $crashes = $this->baseService->getCrashes($car, $request->page, $request->per_page);

        return response($crashes);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $classes = $this->baseService->getCarClasses();
        $statuses = $this->baseService->getStatuses();
        $entities = $this->entityServiceContract->getAllEntities();

        return view('system-worker.traffic-safety.create', compact('classes', 'statuses', 'entities'));
    }

    /**
     * @param $car_id
     * @return Application|Factory|View
     */
    public function edit($car_id)
    {
        $classes = $this->baseService->getCarClasses();
        $statuses = $this->baseService->getStatuses();
        $car = $this->baseService->getFranchiseCar($car_id);
        $entities = $this->entityServiceContract->getAllEntities();

        return view('system-worker.traffic-safety.edit', compact('car', 'classes', 'statuses', 'entities'));
    }

    /**
     * @param  CreateTrafficSafetyRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function store(CreateTrafficSafetyRequest $request)
    {
        $create_car = $this->baseService->createCar($request->validated());

        if (!$create_car) {
            return response(['message' => trans('messages.something_went_wrong')], 500);
        }

        return response(['message' => trans('messages.car_added')]);
    }

    /**
     * @param  UpdateTrafficSafetyRequest  $request
     * @param  int  $car_id
     * @return Application|ResponseFactory|Response
     */
    public function update(UpdateTrafficSafetyRequest $request, int $car_id)
    {
        return $this->baseService->updateCar($car_id, $request->validated())
            ? response(['message' => trans('messages.car_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CreateCrashRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function createCrash(CreateCrashRequest $request)
    {
        $data = $request->only([
            'car_id',
            'driver_id',
            'address',
            'inspector_info',
            'participant_info',
            'description',
            'our_fault',
            'dateTime',
            'act',
            'act_sum',
            'images'
        ]);

        return $this->baseService->createCrash($data)
            ? response(['message' => trans('messages.crash_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $crash
     * @return ResponseFactory|Response
     */
    public function deleteCrash($crash)
    {
        return $this->baseService->deleteCrash($crash)
            ? response(['message' => trans('messages.crash_deleted')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  UpdateTrafficSafetyParkRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function updatePark(UpdateTrafficSafetyParkRequest $request)
    {
        return $this->baseService->updatePark($request)
            ? response(['message' => trans('messages.car_park_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  UpdateTrafficSafetyStatusRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function updateStatus(UpdateTrafficSafetyStatusRequest $request)
    {
        return $this->baseService->updateStatus($request)
            ? response(['message' => trans('messages.car_status_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  UpdateTrafficSafetyInspectionRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function updateInspection(UpdateTrafficSafetyInspectionRequest $request)
    {
        return $this->baseService->updateInspection($request) ?
            response(['message' => trans('messages.car_inspection_updated')], 200) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  UpdateTrafficSafetyInsuranceRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function updateInsurance(UpdateTrafficSafetyInsuranceRequest $request)
    {
        return $this->baseService->updateInsurance($request) ?
            response(['message' => trans('messages.car_insurance_updated')], 200) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  int  $car_id
     * @param  string  $type
     * @return Application|ResponseFactory|Response|BinaryFileResponse
     */
    public function downloadScan(int $car_id, string $type)
    {
        $pdf = $this->baseService->downloadStsPts($car_id, $type);

        if (!$pdf) {
            return response(['message' => 'Error pdf'], 500);
        }

        if (!Storage::disk('local')->exists(str_replace('storage', '', $pdf))) {
            return response(['message' => 'error path'], 500);
        }

        return \Response::download(storage_path(str_replace('storage', 'app', $pdf)));
    }
}
