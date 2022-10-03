<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper\Stations;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Http\Requests\AdminSuper\CreateEditAirportRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Stations\AirportResources;
use Src\Services\SuperAdmin\SuperAdminServiceContract;

/**
 * Class AirportController
 * @package Src\Http\Controllers\AdminSuper\Stations
 */
class AirportController extends StationController
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
    public function getAirports(Request $request)
    {
        $airports = $this->superService->airportsDataTable($request->page, $request->per_page, $request->search, $request->city);

        return (new PaginateResource($airports))->collectionClass(AirportResources::class);
    }

    /**
     * @param $airport_id
     * @param  CreateEditAirportRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function updateAirport(CreateEditAirportRequest $request, $airport_id)
    {
        $updated = $this->superService->updateAirport($airport_id, $request->validated());

        if (!$updated) {
            return response(['message' => 'Error updated'], 500);
        }

        return response(['message' => 'Updated']);
    }

    /**
     * @param  CreateEditAirportRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function createAirport(CreateEditAirportRequest $request)
    {
        $create = $this->superService->createAirport($request->validated());

        if (!$create) {
            return response(['message' => 'Error Server'], 500);
        }

        return response(['message' => 'Airport created', '_payload' => ['airport_id' => $create]]);
    }

    /**
     * @param  Request  $request
     * @param $airport
     * @param $password
     * @return Application|ResponseFactory|Response
     */
    public function deleteAirport(Request $request, $airport)
    {
//        if (!$this->superService->checkComparePassword(user()->name, $password)) {
//            return response(['message' => 'invalid pwd or name'], 422);
//        }

        $delete = $this->superService->deleteAirport((int)$airport);

        if (!$delete) {
            return response(['message' => 'Error deleted operation'], 400);
        }

        return response(['message' => 'Deleted']);
    }
}
