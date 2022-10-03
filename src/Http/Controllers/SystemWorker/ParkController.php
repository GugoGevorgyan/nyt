<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\CreateParkRequest;
use Src\Http\Requests\SystemWorker\GetParksRequest;
use Src\Http\Requests\SystemWorker\UpdateParkRequest;
use Src\Http\Resources\App\ParkResources;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Services\Franchise\FranchiseServiceContract;
use Src\Services\Park\ParkServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\Services\Worker\WorkerService;

/**
 * Class ParkController
 */
class ParkController extends Controller
{
    /**
     * @var ParkServiceContract
     */
    protected ParkServiceContract $parkService;
    /**
     * @var FranchiseServiceContract
     */
    protected FranchiseServiceContract $franchiseService;
    /**
     * @var WorkerService
     */
    protected WorkerService $systemWorkerService;
    /**
     * @var RegionServiceContract
     */
    protected RegionServiceContract $regionService;

    /**
     * ParkController constructor.
     * @param  ParkServiceContract  $parkService
     * @param  FranchiseServiceContract  $franchiseService
     * @param  WorkerService  $systemWorkerService
     * @param  RegionServiceContract  $regionService
     */
    public function __construct(
        ParkServiceContract $parkService,
        FranchiseServiceContract $franchiseService,
        WorkerService $systemWorkerService,
        RegionServiceContract $regionService
    ) {
        $this->parkService = $parkService;
        $this->franchiseService = $franchiseService;
        $this->systemWorkerService = $systemWorkerService;
        $this->regionService = $regionService;
    }

    /**
     * @return Application|Factory|View
     * @throws JsonException
     */
    public function index(): Factory|View|Application
    {
        [$regions, $cities] = $this->franchiseService->getFranchiseRegion();
        $entities = $this->franchiseService->getFranchiseEntities();
        $managers = $this->systemWorkerService->getFranchiseWorkersByRoleName('park_manager_web');

        return view('system-worker.park.index', [
            'regions' => $regions ? $regions->toJson() : json_encode([], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'cities' => $cities ? $cities->toJson() : json_encode([], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'entities' => json_encode($entities, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'managers' => json_encode($managers, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * @param  GetParksRequest  $request
     * @return PaginateResource|BaseResource
     */
    public function paginate(GetParksRequest $request)
    {
        $parks = $this->parkService->parkPaginate($request);

        return (new PaginateResource($parks))->collectionClass(ParkResources::class);
    }

    /**
     * @param  CreateParkRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function store(CreateParkRequest $request)
    {
        return $this->parkService->createPark($request)
            ? response(['message' => trans('messages.park_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  UpdateParkRequest  $request
     * @param $park_id
     * @return Application|ResponseFactory|Response
     */
    public function update(UpdateParkRequest $request, $park_id)
    {
        return $this->parkService->updatePark($request, $park_id)
            ? response(['message' => trans('messages.park_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $park_id
     * @return Application|ResponseFactory|Response
     */
    public function delete($park_id)
    {
        return $this->parkService->deletePark($park_id)
            ? response(['message' => trans('messages.park_deleted')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }
}
