<?php

declare(strict_types=1);

namespace Src\Http\Controllers\DriverApi;

use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JsonException;
use Src\Exceptions\Lexcept;
use Src\Http\Controllers\BaseAuthController;
use Src\Http\Requests\Driver\AuthByPhoneRequest;
use Src\Http\Requests\Driver\DriverAuthRequest;
use Src\Http\Requests\Driver\DriverRefreshTokenRequest;
use Src\Http\Requests\Driver\LogoutRequest;
use Src\Http\Requests\Driver\SetUpdateHashRequest;
use Src\Http\Resources\App\ApiKeysResource;
use Src\Http\Resources\BearerResource;
use Src\Http\Resources\Driver\DriverResource;
use Src\Http\Resources\Driver\SendSmsAuthResource;
use Src\Services\Auth\AuthServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\ServicesCrud\Driver\DriverCrudContract;

/**
 * Class AuthController
 * @package Src\Http\Controllers\ApiDriver\Auth
 */
class AuthController extends BaseAuthController
{
    use AuthenticatesUsers;

    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;
    /**
     * @var DriverServiceContract
     */
    protected DriverServiceContract $driverService;
    /**
     * @var DriverCrudContract
     */
    protected DriverCrudContract $driverCrud;
    /**
     * @var GeocodeServiceContract
     */
    protected GeocodeServiceContract $geoService;

    /**
     * AuthController constructor.
     * @param  AuthServiceContract  $authContract
     * @param  DriverServiceContract  $driverService  $driverService
     * @param  DriverCrudContract  $driverCrud
     * @param  GeocodeServiceContract  $geoService
     */
    public function __construct(
        AuthServiceContract $authContract,
        DriverServiceContract $driverService,
        DriverCrudContract $driverCrud,
        GeocodeServiceContract $geoService
    ) {
        $this->authService = $authContract;
        $this->driverService = $driverService;
        $this->driverCrud = $driverCrud;
        $this->geoService = $geoService;
    }

    /**
     * @param  DriverAuthRequest  $request
     * @return Application|Response|ResponseFactory
     * @throws JsonException
     * @throws Lexcept
     */
    public function auth(DriverAuthRequest $request)
    {
        [$driver, $bearer_token] = $this->authService->authDriver($request->username, $request->password);
        $keys = $this->geoService->getYKeys();

        if (!$driver || !$bearer_token) {
            return response(['message' => 'Driver is not authenticated'], 500);
        }

        $this->guard()->setUser($driver);

        return response(
            [
                'message' => 'Ok',
                'keys' => new ApiKeysResource($keys),
                'driver' => new DriverResource($driver),
                'bearer' => new BearerResource($bearer_token)
            ]
        );
    }

    //TODO auth by phone

    /**
     * @return Guard|StatefulGuard
     */
    public function guard()
    {
        return Auth::guard((string)session('assigned_guard'));
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response|SendSmsAuthResource
     */
    public function sendSmsAcceptCode(Request $request)
    {
        $data = $this->authService->sendSmsAcceptCodeToDriverMobile($request->phone);

        if (!$data) {
            return response(['message' => 'FAILED PHONE NUMBER'], 422);
        }

        return new SendSmsAuthResource($data);
    }

    /**
     * @param  AuthByPhoneRequest  $request
     * @return ResponseFactory|Response
     */
    public function authByPhone(AuthByPhoneRequest $request)
    {
        [$driver, $bearer_token] = $this->authService->authDriverByPhone($request->phone, $request->accept_code);

        if (!$driver || !$bearer_token) {
            return response(['message' => 'Driver is not authenticated'], 500);
        }

        $this->guard()->setUser($driver);

        $driver->loadMissing(['current_franchise', 'franchisee.regions', 'car']);

        return response(
            [
                'message' => 'Ok',
                'driver' => new DriverResource($driver),
                'bearer' => new BearerResource($bearer_token)
            ]
        );
    }

    /**
     * @param  DriverAuthRequest  $request
     * @return ResponseFactory|Response
     */
    public function refreshToken(DriverRefreshTokenRequest $request)
    {
        [$driver, $bearer_token] = $this->authService->getDriverToken($request);

        if (!$driver || !$bearer_token) {
            return response(['message' => 'Driver is not authenticated'], 500);
        }

        $this->guard()->setUser($driver);

        $driver->loadMissing(['current_franchise', 'franchisee.regions', 'car']);

        return response(
            [
                'message' => 'Ok',
                'driver' => new DriverResource($driver),
                'bearer' => new BearerResource($bearer_token)
            ]
        );
    }

    /**
     * @return ResponseFactory|Response
     */
    public function logout(LogoutRequest $request)
    {
        if (!$this->authService->logoutDriver($request)) {
            return \response(['error logout'], 500);
        }

        $this->guard()->guest();

        return response(['message' => 'logout driver'], 204);
    }

    /**
     * @param  SetUpdateHashRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function setUpdatePush(SetUpdateHashRequest $request): Response|Application|ResponseFactory
    {
        $this->authService->setUpdatePushKey(get_user_id(), user()->getMap(), $request->uid);

        return response(['message' => 'updated', 'status' => 'OK']);
    }
}
