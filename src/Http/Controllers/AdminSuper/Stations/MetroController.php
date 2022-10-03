<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper\Stations;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Http\Requests\AdminSuper\CreateEditAirportRequest;
use Src\Http\Requests\AdminSuper\CreateEditMetroRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Stations\MetroResources;
use Src\Services\SuperAdmin\SuperAdminServiceContract;

/**
 * Class MetroController
 * @package Src\Http\Controllers\AdminSuper\Stations
 */
class MetroController extends StationController
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
    public function getMetros(Request $request)
    {
        $metros = $this->superService->metrosDataTable($request->page, $request->per_page, $request->search, $request->city);

        return (new PaginateResource($metros))->collectionClass(MetroResources::class);
    }


    /**
     * @param  CreateEditMetroRequest  $request
     * @param $metro_id
     * @return Application|ResponseFactory|Response
     */
    public function updateMetro(CreateEditMetroRequest $request, $metro_id)
    {
        $updated = $this->superService->updateMetro($metro_id, $request->validated());

        if (!$updated) {
            return response(['message' => 'Error updated'], 500);
        }

        return response(['message' => 'Updated']);
    }

    /**
     * @param  CreateEditMetroRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function createMetro(CreateEditMetroRequest $request)
    {
        $create = $this->superService->createMetro($request->validated());

        if (!$create) {
            return response(['message' => 'Error Server'], 500);
        }

        return response(['message' => 'Metro created', '_payload' => ['metro_id' => $create]]);
    }

    /**
     * @param  Request  $request
     * @param $metro
     * @return Application|ResponseFactory|Response
     */
    public function deleteMetro(Request $request, $metro)
    {
        if (!$this->superService->checkComparePassword($request->name, $request->password)) {
            return response(['message' => 'invalid pwd or name'], 422);
        }

        $delete = $this->superService->deleteMetro($metro);

        if (!$delete) {
            return response(['message' => 'Error deleted operation'], 400);
        }

        return response(['message' => 'Deleted']);
    }
}
