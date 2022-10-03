<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\ContractSigningRequest;
use Src\Services\DriverContract\DriverContractServiceContract;

/**
 * Class ContractSigningController
 * @package Src\Http\Controllers\SystemWorker
 */
class ContractSigningController extends Controller
{
    /**
     * @var DriverContractServiceContract
     */
    protected DriverContractServiceContract $driverContractService;

    /**
     * ContractSigningController constructor.
     * @param  DriverContractServiceContract  $driverContractService
     */
    public function __construct(DriverContractServiceContract $driverContractService)
    {
        $this->driverContractService = $driverContractService;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('system-worker.contract-signing.index');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function paginate(Request $request)
    {
        return $this->driverContractService->unsignedDriversPaginate($request);
    }

    /**
     * @param  ContractSigningRequest  $request
     * @return ResponseFactory|Response
     */
    public function printContract(ContractSigningRequest $request)
    {
        $contract = $this->driverContractService->createContractFile($request);

        if (!$contract) {
            return response(['message' => 'Oops, something went wrong!'], 400);
        }

        return response($contract);
    }

    /**
     * @param $driver_contract_id
     * @return ResponseFactory|Response
     */
    public function sign($driver_contract_id)
    {
        $contract = $this->driverContractService->signContract($driver_contract_id);

        if (!$contract) {
            return response(['message' => 'Oops, something went wrong!'], 400);
        }

        return response(['message' => 'Contract accepted'], 200);
    }
}
