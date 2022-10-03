<?php

namespace Src\Http\Controllers\SystemWorker;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\DriverScheduleUpdateRequest;
use Src\Repositories\DriverGraphic\DriverGraphicContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\DriverSchedule\DriverScheduleService;
use Src\Services\Park\ParkServiceContract;
use Src\Services\Worker\WorkerService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * ScheduleController constructor.
     * @param WorkerService $systemWorkerService
     * @param DriverScheduleService $driverScheduleService
     * @param ParkServiceContract $parkServiceContract
     * @param DriverServiceContract $driverServiceContract
     */
    public function __construct(
        protected WorkerService $systemWorkerService,
        protected DriverScheduleService $driverScheduleService,
        protected ParkServiceContract $parkServiceContract,
        protected DriverServiceContract $driverServiceContract,
        protected DriverGraphicContract $driverGraphicContract
    ) {
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $parks = $this->parkServiceContract->getFranchiseParks();
        $types = $this->driverServiceContract->getTypes();
        $graphics = $this->driverGraphicContract->findAll();

        return view('system-worker.schedule.index', ['parks' => $parks, 'types' => $types, 'graphics' => $graphics]);
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function paginate(Request $request)
    {
        return $this->driverScheduleService->driversPaginate($request);
    }

    /**
     * @param  DriverScheduleUpdateRequest  $request
     * @return Application|ResponseFactory|Response
     * @throws Exception
     */
    public function update(DriverScheduleUpdateRequest $request)
    {
        return $this->driverScheduleService->updateDriverSchedule($request)
            ? response(['message' => trans('messages.driver_schedule_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

}
