<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\GetClientsRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Worker\ClientsPaginateResource;
use Src\Services\Worker\WorkerServiceContract;

/**
 *
 */
class ClientController extends Controller
{
    public function __construct(protected WorkerServiceContract $workerService)
    {
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('system-worker.clients.index');
    }

    /**
     * @param  GetClientsRequest  $request
     * @return Application|ResponseFactory|Response|BaseResource|PaginateResource
     */
    public function pager(GetClientsRequest $request): Response|PaginateResource|BaseResource|Application|ResponseFactory
    {
        $pager = $this->workerService->getClients($request->validated());

        if (!$pager->count()) {
            return response(['message' => 'Error data'], 500);
        }

        return (new PaginateResource($pager))->collectionClass(ClientsPaginateResource::class);
    }
}
