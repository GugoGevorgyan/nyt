<?php

declare(strict_types=1);

namespace Src\Http\Controllers\WorkerApi;

use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\CarReport\WorkerLoginRequest;
use Src\Http\Requests\CarReport\WorkerRefreshTokenRequest;
use Src\Http\Resources\BearerResource;
use Src\Http\Resources\Models\WorkerResource;
use Src\Http\Resources\Worker\Mechanic\AuthResource;
use Src\Services\Auth\AuthServiceContract;
use Src\Services\Car\CarServiceContract;

/**
 * Class AuthController
 * @package Src\Http\Controllers\Mechanic
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var CarServiceContract
     */
    protected CarServiceContract $carService;
    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;

    /**
     * AuthController constructor.
     * @param  CarServiceContract  $carReportService
     * @param  AuthServiceContract  $authService
     */
    public function __construct(CarServiceContract $carReportService, AuthServiceContract $authService)
    {
        $this->carService = $carReportService;
        $this->authService = $authService;
    }

    /**
     * @param  WorkerLoginRequest  $request
     * @return Application|ResponseFactory|Response|AuthResource
     */
    public function login(WorkerLoginRequest $request)
    {
        $worker = $this->authService->workerBearer($request);

        if (!$worker) {
            return response(['message' => 'WorkerWeb is not authenticated'], 500);
        }

        return new AuthResource($worker);
    }

    /**
     * @param  WorkerRefreshTokenRequest  $request
     * @return ResponseFactory|Response
     */
    public function refreshToken(WorkerRefreshTokenRequest $request)
    {
        [$has_mechanic, $bearer_token] = $this->carService->mechanicRefreshToken($request);

        if (!$has_mechanic || !$bearer_token) {
            return response(['message' => 'Mechanic is not authenticated'], 500);
        }

        $has_mechanic->load(['franchise']);

        return response(
            [
                'message' => 'Ok',
                'driver' => new WorkerResource($has_mechanic),
                'bearer' => new BearerResource($bearer_token)
            ]
        );
    }

    /**
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function logoutWorker(Request $request)
    {
        if (!$this->carService->logoutWorker($request)) {
            return response(['error logout'], 500);
        }

        $this->guard()->guest();

        return response(['message' => 'User logout successful']);
    }

    /**
     * @return Guard|StatefulGuard
     */
    public function guard()
    {
        return Auth::guard((string)session('assigned_guard'));
    }

}
