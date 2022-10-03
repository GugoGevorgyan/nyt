<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\DriverContractTerminateRequest;
use Src\Services\DriverContract\DriverContractServiceContract;

/**
 * Class DriverContractController
 * @package Src\Http\Controllers\SystemWorker
 */
class DriverContractController extends Controller
{
    /**
     * @var DriverContractServiceContract
     */
    protected $driverContractService;

    /**
     * DriverContractController constructor.
     * @param  DriverContractServiceContract  $driverContractService
     */
    public function __construct(DriverContractServiceContract $driverContractService)
    {
        $this->driverContractService = $driverContractService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('system-worker.driver-contracts.index');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function paginate(Request $request)
    {
        return $this->driverContractService->driverContractsPaginate($request);
    }

    /**
     * @param  DriverContractTerminateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function terminate(DriverContractTerminateRequest $request)
    {
        if (!Hash::check($request->password, user()->password)) {
            return response(['message' => 'invalid pwd'], 422);
        }

        $terminate = $this->driverContractService->terminateContract($request);

        if (!$terminate) {
            return response(['message' => 'Oops! Something went wrong'], 500);
        }

        return response(['message' => 'Contract successfully terminated', '_payload' => $terminate->updated_at], 200);
    }

    public function editDriverContractPrice(Request $request)
    {
        $result = $this->driverContractService->updateDriverContractPrice($request->all());

        if ($result) {
            return response(['message' => 'Цена контракта успешно обновлена'], 200);
        }

        return response(['message' => 'Server error'], 500);
    }
}
