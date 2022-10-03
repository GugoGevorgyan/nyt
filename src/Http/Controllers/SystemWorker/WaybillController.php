<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Put;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\WaybillsCreateRequest;
use Src\Http\Requests\SystemWorker\WaybillsPaginateRequest;
use Src\Http\Requests\SystemWorker\WaybillsRestoreRequest;
use Src\Http\Resources\Driver\DriverWaybillResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Worker\Waybill\WaybillData;
use Src\Http\Resources\Worker\Waybill\WaybillReportImagesResource;
use Src\Http\Resources\Worker\Waybill\WaybillReportResource;
use Src\Repositories\Driver\DriverContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\ParkManagement\ParkManagementServiceContract;
use Src\Services\Worker\WorkerServiceContract;
use Src\Services\Terminal\TerminalServiceContract;
use Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class WaybillController
 * @package Src\Http\Controllers\SystemWorker
 */
class WaybillController extends Controller
{
    /**
     * WaybillController constructor.
     * @param  WorkerServiceContract  $workerService
     * @param  ParkManagementServiceContract  $parkManagement
     * @param  DriverServiceContract  $driverService
     * @param  TerminalServiceContract  $terminalService
     * @param  DriverContract  $driverContract
     */
    public function __construct(
        protected WorkerServiceContract $workerService,
        protected ParkManagementServiceContract $parkManagement,
        protected DriverServiceContract $driverService,
        protected TerminalServiceContract $terminalService,
        protected DriverContract $driverContract
    ) {
    }

    /**
     * @return Application|Factory|View
     */
    public function getWaybillsIndex(): View|Factory|Application
    {
        $parks = $this->parkManagement->getParks(['park_id', 'name']);
        $drivers = $this->driverContract->getFranchiseDrivers(FRANCHISE_ID, ['driver_id', 'driver_info_id']);

        return \view('system-worker.waybill.index', compact('parks', 'drivers'));
    }

    /**
     * @param  WaybillsPaginateRequest  $request
     * @return Application|ResponseFactory|Response|PaginateResource
     */
    public function getWaybillsPaginate(WaybillsPaginateRequest $request): Response|PaginateResource|Application|ResponseFactory
    {
        $waybills = $this->workerService->getFranchiseWaybills($request->page, $request->per_page, $request->filter ?? []);

        if (!$waybills) {
            return response(['message' => 'Something went wrong'], 400);
        }

        return (new PaginateResource($waybills))->collectionClass(WaybillData::class);
    }

    /**
     * @param $waybill_id
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     * @Get("waybill/info/{waybill_id}", where={"order_id": "[0-9]+"}, no_prefix="true", name="get_waybill_info")
     */
    public function getWaybillInfo($waybill_id): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $waybill = $this->workerService->getWaybillDetails($waybill_id);

        if (!$waybill) {
            return response(['message' => 'Something went wrong'], 400);
        }

        return WaybillReportResource::collection($waybill);
    }

    /**
     * @param $waybill_id
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     * @Get("waybill/info/{waybill_id}", where={"order_id": "[0-9]+"}, no_prefix="true", name="get_waybill_info")
     */
    public function getWaybillImages($waybill_id): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $waybill = $this->workerService->getWaybillImages($waybill_id);

        if (!$waybill) {
            return response(['message' => 'Something went wrong'], 400);
        }

        return WaybillReportImagesResource::collection($waybill);
    }

    /**
     * @param $waybill_id
     * @Put("waybill/annul/{waybill_id}", where={"waybill_id": "[0-9]+"}, no_prefix="true", name="annul_waybill")
     * @return Application|ResponseFactory|Response
     */
    public function annulWaybill($waybill_id): Response|Application|ResponseFactory
    {
        $annulled = $this->workerService->annulWaybill($waybill_id);

        return response(['message' => 'Waybill Annulled', '_payload' => ['annulled' => $annulled]]);
    }

    /**
     * @param $waybill_id
     * @return Application|ResponseFactory|Response|void
     * @throws FileNotFoundException
     */
    public function printWaybill($waybill_id)
    {
        $waybill = $this->workerService->printWaybill($waybill_id);

        if (!$waybill) {
            return response(['message' => 'Error scan file not found'], 500);
        }

        if (!Storage::disk('local')->exists(str_replace('storage', '', $waybill))) {
            return response(['message' => 'error path'], 500);
        }

        file_put_contents('php://output', Storage::disk('local')->get(str_replace('storage', '', $waybill)));
    }

    /**
     * @param  string  $search
     * @return AnonymousResourceCollection
     */
    public function searchDrivers(string $search): AnonymousResourceCollection
    {
        $drivers = $this->driverService->searchDrivers($search);

        return DriverWaybillResource::collection($drivers);
    }

    /**
     * @param  WaybillsCreateRequest  $request
     * @return Response
     */
    public function create(WaybillsCreateRequest $request): Response
    {
        $response = $this->terminalService->createManualWaybill($request->driver_id, $request->days, (bool)$request->checked);

        return response($response, $response['success'] ? 200 : 400);
    }

    /**
     * @param  WaybillsRestoreRequest  $request
     * @return Response
     */
    public function restoreCurrent(WaybillsRestoreRequest $request): Response
    {
        $this->terminalService->restoreCurrentWaybill($request->driver_id);

        return response([
            'success' => true,
            'message' => 'Путевый лист восстановлен'
        ]);
    }

    /**
     * @param  int  $waybill_id
     * @param  bool|string|int  $checked
     * @return Application|ResponseFactory|Response
     */
    protected function waybillToggleChecked(int $waybill_id, $checked): Response|Application|ResponseFactory
    {
        $toggle_checked = $this->terminalService->isToggleCheckedWaybill($waybill_id, filter_var($checked, FILTER_VALIDATE_BOOLEAN));

        if (!$toggle_checked) {
            return response(['message' => 'error data'], 500);
        }

        return response(['message' => 'Waybill is toggle checked']);
    }
}
