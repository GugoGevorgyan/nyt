<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\CreateSystemWorkerRequest;
use Src\Http\Requests\SystemWorker\UpdateSystemWorkerRequest;
use Src\Http\Requests\SystemWorker\WorkerDeleteMultipleRequest;
use Src\Http\Requests\SystemWorker\WorkerDeleteRequest;
use Src\Services\Franchise\FranchiseServiceContract;
use Src\Services\Worker\WorkerServiceContract;

use function in_array;

/**
 * Class WorkerController
 * @package Src\Http\Controllers\SystemWorker
 */
class WorkerController extends Controller
{
    /**
     * @var WorkerServiceContract
     */
    protected WorkerServiceContract $baseService;

    /**
     * @var FranchiseServiceContract
     */
    protected FranchiseServiceContract $franchiseServiceContract;

    /**
     * WorkerController constructor.
     * @param  WorkerServiceContract  $baseService
     * @param  FranchiseServiceContract  $franchiseServiceContract
     */
    public function __construct(
        WorkerServiceContract $baseService,
        FranchiseServiceContract $franchiseServiceContract
    ) {
        $this->baseService = $baseService;
        $this->franchiseServiceContract = $franchiseServiceContract;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('system-worker.system-workers.index', ['roles' => $this->franchiseServiceContract->getFranchiseRoles()]);
    }

    /**
     * @param  Request  $request
     * @return LengthAwarePaginator
     */
    public function paginate(Request $request): LengthAwarePaginator
    {
        return $this->baseService->workersPaginate($request);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $sub_phones = $this->franchiseServiceContract->getFranchiseSubPhones(FRANCHISE_ID);
        $module_roles = $this->franchiseServiceContract->getFranchiseModuleRoles(FRANCHISE_ID);

        return view('system-worker.system-workers.create', compact('module_roles', 'sub_phones'));
    }

    /**
     * @param  CreateSystemWorkerRequest  $request
     * @return ResponseFactory|Response
     */
    public function store(CreateSystemWorkerRequest $request)
    {
        return $this->baseService->createSystemWorker($request)
            ? response(['message' => trans('messages.system_worker_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $worker_id
     * @return Factory|View
     * @throws JsonException
     */
    public function edit($worker_id)
    {
        $worker = $this->baseService->getWorkerById($worker_id);

        if (!$worker) {
            return view('errors.404');
        }

        $sub_phones = $this->franchiseServiceContract->getFranchiseSubPhones(FRANCHISE_ID);
        $module_roles = $this->franchiseServiceContract->getFranchiseModuleRoles(FRANCHISE_ID);

        return view(
            'system-worker.system-workers.edit',
            [
                'worker' => json_encode($worker, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'module_roles' => json_encode($module_roles, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'sub_phones' => $sub_phones
            ]
        );
    }

    /**
     * @param  UpdateSystemWorkerRequest  $request
     * @param $system_worker_id
     * @return Application|ResponseFactory|Response
     */
    public function update(UpdateSystemWorkerRequest $request, $system_worker_id): Response|Application|ResponseFactory
    {
        return $this->baseService->updateWorker($request, $system_worker_id)
            ? response(['message' => trans('messages.system_worker_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  WorkerDeleteRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function destroy(WorkerDeleteRequest $request)
    {
        if ($request->worker_id === auth()->user()->system_worker_id) {
            return response(['message' => trans('messages.cant_delete_yourself')], 400);
        }

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response(['message' => trans('messages.wrong_password')], 400);
        }

        return $this->baseService->deleteWorker($request->worker_id)
            ? response(['message' => trans('messages.system_worker_deleted')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  WorkerDeleteMultipleRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function destroyMultiple(WorkerDeleteMultipleRequest $request)
    {
        if (in_array(auth()->user()->system_worker_id, $request->worker_ids, true)) {
            return response(['message' => trans('messages.cant_delete_yourself')], 400);
        }

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response(['message' => trans('messages.wrong_password')], 400);
        }

        return $this->baseService->deleteWorkers($request->worker_ids) ?
            response(['message' => trans('messages.system_workers_deleted')], 200) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }
}
