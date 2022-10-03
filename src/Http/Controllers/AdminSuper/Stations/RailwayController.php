<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper\Stations;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Http\Requests\AdminSuper\CreateEditRailwayRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Stations\RailwayResources;
use Src\Services\SuperAdmin\SuperAdminServiceContract;

/**
 * Class RailwaysController
 * @package Src\Http\Controllers\AdminSuper\Stations
 */
class RailwayController extends StationController
{
    /**
     * @var SuperAdminServiceContract
     */
    protected SuperAdminServiceContract $superService;

    /**
     * AirportController constructor.
     * @param  SuperAdminServiceContract  $superService
     */
    public function __construct(SuperAdminServiceContract $superService)
    {
        $this->superService = $superService;
    }

    /**
     * @param  Request  $request
     * @return BaseResource|PaginateResource
     */
    public function getRailways(Request $request)
    {
        $railway = $this->superService->railwayDataTable($request->page, $request->per_page, $request->search, $request->city);

        return (new PaginateResource($railway))->collectionClass(RailwayResources::class);
    }


    /**
     * @param  CreateEditRailwayRequest  $request
     * @param $railway
     * @return Application|ResponseFactory|Response
     */
    public function updateRailway(CreateEditRailwayRequest $request, $railway)
    {
        $updated = $this->superService->updateRailway($railway, $request->validated());

        if (!$updated) {
            return response(['message' => 'Error updated'], 500);
        }

        return response(['message' => 'Updated']);
    }

    /**
     * @param  CreateEditRailwayRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function createRailway(CreateEditRailwayRequest $request)
    {
        $create = $this->superService->createRailway($request->validated());

        if (!$create) {
            return response(['message' => 'Error Server'], 500);
        }

        return response(['message' => 'Railway created', '_payload' => ['railway_id' => $create]]);
    }

    /**
     * @param  Request  $request
     * @param $railway
     * @return Application|ResponseFactory|Response
     */
    public function deleteRailway(Request $request, $railway)
    {
        if (!$this->superService->checkComparePassword($request->name, $request->password)) {
            return response(['message' => 'invalid pwd or name'], 422);
        }

        $delete = $this->superService->deleteRailway($railway);

        if (!$delete) {
            return response(['message' => 'Error deleted operation'], 400);
        }

        return response(['message' => 'Deleted']);
    }
}
