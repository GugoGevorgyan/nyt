<?php

declare(strict_types=1);

namespace Src\Http\Controllers\Terminal;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Terminal\AuthRequest;
use Src\Http\Requests\Terminal\DriverAuthRequest;
use Src\Http\Resources\Terminal\AuthResource;
use Src\Http\Resources\Terminal\DriverAuthResource;
use Src\Repositories\Driver\DriverContract;
use Src\Services\Auth\AuthServiceContract;
use Src\Services\Terminal\TerminalServiceContract;

/**
 * Class AuthController
 * @package Src\Http\Controllers\Terminal
 */
class AuthController extends Controller
{
    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;
    /**
     * @var TerminalServiceContract
     */
    protected TerminalServiceContract $terminalService;
    /**
     * @var DriverContract
     */
    protected DriverContract $driverContract;

    /**
     * IndexController constructor.
     * @param  AuthServiceContract  $authService
     * @param  TerminalServiceContract  $terminalService
     * @param  DriverContract  $driverContract
     */
    public function __construct(AuthServiceContract $authService, TerminalServiceContract $terminalService, DriverContract $driverContract)
    {
        $this->authService = $authService;
        $this->terminalService = $terminalService;
        $this->driverContract = $driverContract;
    }

    /**
     * @param  AuthRequest  $request
     * @return Application|ResponseFactory|Response|AuthResource
     */
    public function auth(AuthRequest $request)
    {
        $create_token = $this->authService->authTerminal($request->terminal_name, $request->password);

        if (!$create_token) {
            return response(['message' => 'Server Error'], 500);
        }

        return new AuthResource($create_token);
    }

    /**
     * @param  DriverAuthRequest  $request
     * @return Application|ResponseFactory|Response|DriverAuthResource
     */
    public function authDriver(DriverAuthRequest $request)
    {
        $auth = $this->authService->authTerminalDriver($request->login, $request->password, (int)user()->{user()->getKeyName()});

        if (!$auth) {
            return response(['message' => 'FAILED DATA'], 500);
        }

        return new DriverAuthResource($auth);
    }
}
