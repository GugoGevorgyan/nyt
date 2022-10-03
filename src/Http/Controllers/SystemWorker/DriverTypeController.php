<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\UpdateFranchiseOptionals;
use Src\Repositories\DriverTypeOptional\DriverTypeOptionalContract;
use Src\Services\Driver\DriverServiceContract;

/**
 * Class DriverTypeController
 * @package Src\Http\Controllers\SystemWorker
 */
class DriverTypeController extends Controller
{
    /**
     * DriverTypeController constructor.
     * @param  DriverServiceContract  $driverService
     * @param  DriverTypeOptionalContract  $optionalContract
     */
    public function __construct(
        protected DriverServiceContract $driverService,
        protected DriverTypeOptionalContract $optionalContract
    ) {
    }


    /**
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $result = $this->driverService->getTypesWithOptions();

        return view('system-worker.driver-types.index', ['types' => $result->get('types'), 'options' => $result->get('options')]);
    }

    /**
     * @param  UpdateFranchiseOptionals  $request
     * @return Application|ResponseFactory|Response
     */
    public function updateFranchiseOptionals(UpdateFranchiseOptionals $request): Response|Application|ResponseFactory
    {
        $update = $this->driverService->updateFranchiseOptionals($request);

        if (!$update) {
            return response(['message' => 'Oops, something went wrong!'], 400);
        }

        return response(['message' => 'Options updated', 'franchise_options' => $update]);
    }
}
