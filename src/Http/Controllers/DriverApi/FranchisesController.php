<?php

declare(strict_types=1);

namespace Src\Http\Controllers\DriverApi;

use Collective\Annotations\Routing\Annotations\Annotations\Put;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Driver\UpdateFranchiseRequest;
use Src\Http\Resources\Driver\DriverResource;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Franchise\FranchiseServiceContract;
use Src\ServicesCrud\Driver\DriverCrudContract;

/**
 * Class FranchisesController
 * @package Src\Http\Controllers\ApiDriver
 */
class FranchisesController extends Controller
{
    /**
     * @var FranchiseServiceContract
     */
    protected FranchiseServiceContract $franchiseServiceContract;
    /**
     * @var DriverServiceContract
     */
    protected DriverServiceContract $driverServiceContract;
    /**
     * @var DriverCrudContract
     */
    protected DriverCrudContract $driverCrud;

    /**
     * FranchisesController constructor.
     * @param  FranchiseServiceContract  $franchiseServiceContract
     * @param  DriverServiceContract  $driverServiceContract
     * @param  DriverCrudContract  $driverCrud
     */
    public function __construct(
        FranchiseServiceContract $franchiseServiceContract,
        DriverServiceContract $driverServiceContract,
        DriverCrudContract $driverCrud
    ) {
        $this->franchiseServiceContract = $franchiseServiceContract;
        $this->driverServiceContract = $driverServiceContract;
        $this->driverCrud = $driverCrud;
    }

    /**
     * @param  UpdateFranchiseRequest  $request
     * @return ResponseFactory|Response|DriverResource
     */
    public function update(UpdateFranchiseRequest $request)
    {
        $driver = $this->driverCrud->updateFranchise(get_user_id(), $request->franchise_id);

        if (!$driver) {
            return response(['message' => 'Driver franchise not updated'], 500);
        }

        return response(['message' => 'Franchise selected', 'status' => 'OK']);
    }
}
