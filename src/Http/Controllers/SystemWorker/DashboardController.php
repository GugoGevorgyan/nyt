<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\StartSessionRequest;
use Src\Services\MenuService\MenuServiceContract;
use Src\Services\Worker\WorkerServiceContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package Src\Http\Controllers\SystemWorker
 */
class DashboardController extends Controller
{
    /**
     * CorporateClientController constructor.
     * @param  MenuServiceContract  $menuServiceContract
     * @param  WorkerServiceContract  $systemWorkerService
     */
    public function __construct(protected MenuServiceContract $menuServiceContract, protected WorkerServiceContract $systemWorkerService)
    {
    }

    /**
     * @return Factory|View
     */
    public function showDashboardPage()
    {
        return view('system-worker.index');
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function stopSession(Request $request)
    {
        $token = $this->systemWorkerService->stopSession(user());

        if (!$token) {
            return response(['message' => 'Failed data'], 500);
        }

        return response(['message' => 'Session Stopped', '_payload' => ['keyValue' => $token]]);
    }

    /**
     * @param StartSessionRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function startSession(StartSessionRequest $request)
    {
        $result = $this->systemWorkerService->startSession(user(), $request->nickname, $request->password, $request->token);

        if (!$result) {
            return response(['message' => 'Started Failed'], 500);
        }

        return response(['message' => 'OK']);
    }
}
