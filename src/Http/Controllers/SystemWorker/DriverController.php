<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\DriverBlockRequest;
use Src\Http\Requests\SystemWorker\UpdateDriverInfoRequest;
use Src\Http\Resources\App\CoordinateResourse;
use Src\Http\Resources\Worker\DriverBlockResource;
use Src\Repositories\DriverGraphic\DriverGraphicContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Park\ParkServiceContract;
use Src\Services\Worker\WorkerService;
use Src\ServicesCrud\Driver\DriverCrudContract;

/**
 * Class DriverController
 * @package Src\Http\Controllers\SystemWorker\PersonalDepartment
 */
class DriverController extends Controller
{
    /**
     * DriverController constructor.
     * @param  DriverServiceContract  $baseService
     * @param  WorkerService  $systemWorkerService
     * @param  ParkServiceContract  $parkService
     * @param  DriverCrudContract  $baseCrud
     */
    public function __construct(
        protected DriverServiceContract $baseService,
        protected WorkerService $systemWorkerService,
        protected ParkServiceContract $parkService,
        protected DriverCrudContract $baseCrud,
        protected DriverGraphicContract $driverGraphicContract
    ) {
    }

    /**
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $parks = $this->parkService->getFranchiseParks();
        $graphics = $this->driverGraphicContract->findAll();
        $types = $this->baseService->getTypes();

        return view(
            'system-worker.driver.index',
            compact('parks', 'graphics', 'types')
        );
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function paginate(Request $request): mixed
    {
        return $this->baseCrud->franchiseDriversPaginate($request);
    }

    /**
     * @param  DriverBlockRequest  $request
     * @return DriverBlockResource
     */
    public function blockDriver(DriverBlockRequest $request): DriverBlockResource
    {
        $result = $this->baseCrud->blockDriver($request->id, $request->minute);

        return (new DriverBlockResource($result));
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function unBlockDriver(Request $request): Response|Application|ResponseFactory
    {
        $this->baseCrud->unBlockDriver($request->id);

        return response(['message' => 'ok']);
    }

    /**
     * @param  int  $driver_id
     * @param  string|null  $date
     * @return AnonymousResourceCollection
     */
    public function getRoadDriver(int $driver_id, string $date = null): AnonymousResourceCollection
    {
        $new_date = $date ? Carbon::parse($date)->format('Y-m-d') : now()->format('Y-m-d');

        $trajectory = $this->baseCrud->getTrajectoryByDate($driver_id, $new_date);

        return CoordinateResourse::collection($trajectory)->additional(['distance' => $trajectory['distance'] ?? null ? round($trajectory['distance'], 2) : 0]);
    }

    /**
     * @param  UpdateDriverInfoRequest  $request
     * @param $driver_id
     * @param $driver_info_id
     * @return Application|ResponseFactory|Response
     */
    public function updateDriver(UpdateDriverInfoRequest $request, $driver_id, $driver_info_id)
    {
        $password_updated = true;
        $result = $this->baseService->updateDriverInfoFields($driver_id, $driver_info_id, $request->all());

        if ($request['driver']['password']) {
            $new_password_hash = [
                'password' => Hash::make($request['driver']['password'])
            ];
            $password_updated = $this->baseService->updateDriverPassword($driver_id, $new_password_hash);
        }

        if (!$result && !$password_updated) {
            return response(['message' => 'Ops failed'], 500);
        }

        return response(['message' => trans('messages.driver_updated_admin_worker')], 200);
    }
}
