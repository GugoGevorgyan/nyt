<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AppMobile;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\ClientMobile\AddPushKeyRequest;
use Src\Http\Requests\ClientMobile\AuthByDataRequest;
use Src\Http\Requests\ClientMobile\LoginRequest;
use Src\Http\Requests\ClientMobile\LogoutRequest;
use Src\Http\Requests\ClientMobile\RegisterRequest;
use Src\Http\Requests\Driver\SetUpdateHashRequest;
use Src\Http\Resources\Client\RegisterResource;
use Src\Http\Resources\ClientMobile\LoginAuthResource;
use Src\Services\Auth\AuthServiceContract;


/**
 * Class AuthController
 * @package Src\Http\Controllers\AppMobile
 */
class AuthController extends Controller
{
    protected AuthServiceContract $authService;

    /**
     * AuthController constructor.
     * @param  AuthServiceContract  $authService
     */
    public function __construct(AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param  RegisterRequest  $request
     * @return RegisterResource
     * @throws Exception
     */
    public function register(RegisterRequest $request): RegisterResource
    {
        $result = $this->authService->clientMobileAuthenticate($request->validated()['phone']);

        return new RegisterResource($result);
    }

    /**
     * @param  LoginRequest  $request
     * @return Application|ResponseFactory|Response|LoginAuthResource
     */
    public function login(LoginRequest $request)
    {
        $result = $this->authService->clientMobileAuthAccept($request->validated()['phone'], $request->validated()['accept_code']);

        if (!$result) {
            return response(['message' => 'failed'], 400);
        }

        return (new LoginAuthResource($result))->additional(['message' => 'ok']);
    }

    /**
     * @param  AuthByDataRequest  $request
     * @return Application|ResponseFactory|Response|LoginAuthResource
     */
    public function loginByData(AuthByDataRequest $request)
    {
        $auth = $this->authService->clientAuthByEmail($request->email, $request->password);

        if (!$auth) {
            return response(['message' => 'invalid data'], 423);
        }

        return (new LoginAuthResource($auth))->additional(['message' => 'ok']);
    }

    /**
     * @param  LogoutRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function logout(LogoutRequest $request)
    {
        $this->authService->clientMobileLogout(user());

        return response(['message' => 'Logged out', 'status' => 'OK']);
    }

    /**
     * @param  AddPushKeyRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function pushKeySet(AddPushKeyRequest $request)
    {
        $user = user();
        $this->authService->setUpdatePushKey((int)$user->getKeyName(), (string)$user, $request->push_uid);

        return response(['message' => 'ok']);
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
