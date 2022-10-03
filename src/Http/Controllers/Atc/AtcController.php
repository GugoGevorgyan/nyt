<?php

declare(strict_types=1);

namespace Src\Http\Controllers\Atc;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Api\AtcAuthRequest;
use Src\Http\Requests\Api\AtcCallReceivingDataRequest;
use Src\Http\Resources\Atc\AtcAuthResource;
use Src\Http\Resources\Atc\AtcCallResource;
use Src\Repositories\ClientCall\ClientCallContract;
use Src\Services\Auth\AuthServiceContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\ClientCall\ClientCallServiceContract;
use Src\Services\Worker\WorkerServiceContract;

/**
 * Class AtcController
 * @package Src\Http\Controllers\Api
 */
class AtcController extends Controller
{
    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;
    /**
     * @var ClientCallContract
     */
    protected $callService;
    /**
     * @var ClientServiceContract
     */
    protected ClientServiceContract $clientService;
    /**
     * @var WorkerServiceContract
     */
    protected WorkerServiceContract $workerService;

    /**
     * AtcController constructor.
     * @param  AuthServiceContract  $authService
     * @param  ClientCallServiceContract  $callService
     * @param  ClientServiceContract  $clientService
     * @param  WorkerServiceContract  $workerService
     */
    public function __construct(
        AuthServiceContract $authService,
        ClientCallServiceContract $callService,
        ClientServiceContract $clientService,
        WorkerServiceContract $workerService
    ) {
        $this->authService = $authService;
        $this->callService = $callService;
        $this->clientService = $clientService;
        $this->workerService = $workerService;
    }

    /**
     * @param  AtcAuthRequest  $request
     * @return AtcAuthResource|ResponseFactory|Response
     */
    public function auth(AtcAuthRequest $request)
    {
        $result = $this->authService->atcAuth($request->all());

        if (!$result) {
            return response(['message' => 'Data Error', 'status' => 'FAIL'], 500);
        }

        return new AtcAuthResource($result);
    }

    /**
     * @param  AtcCallReceivingDataRequest  $request
     * @return AtcCallResource|JsonResponse
     */
    public function callReceivingData(AtcCallReceivingDataRequest $request)
    {
        return $this->callService->callReceivingData($request);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function connectOperator(Request $request)
    {
        return response(['message' => 'ok'], 200);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function callEnd(Request $request)
    {
        return response(['message' => 'ok'], 200);
    }

    public function debtHook(Request $request)
    {
        write($request->all());
    }
}
