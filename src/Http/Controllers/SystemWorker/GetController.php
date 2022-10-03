<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\DriverStatus\DriverStatusContract;
use Src\Repositories\Metro\MetroContract;
use Src\Repositories\Permission\PermissionContract;
use Src\Repositories\RailwayStation\RailwayStationContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Franchise\FranchiseServiceContract;
use Src\Services\LegalEntity\LegalEntityServiceContract;
use Src\Services\Module\ModuleServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\Services\Role\RoleServiceContract;
use Src\Services\Worker\WorkerServiceContract;

/**
 * Class GetController
 * @package Src\Http\Controllers\SystemWorker
 */
class GetController extends Controller
{
    /**
     * GetController constructor.
     * @param  FranchiseServiceContract  $franchiseService
     * @param  RegionServiceContract  $regionService
     * @param  LegalEntityServiceContract  $entityService
     * @param  WorkerServiceContract  $systemWorkerService
     * @param  CarClassContract  $carClassContract
     * @param  PaymentServiceContract  $paymentTypeService
     * @param  DriverServiceContract  $driverService
     * @param  OrderServiceContract  $orderService
     * @param  RailwayStationContract  $railwayStationContract
     * @param  AirportContract  $airportContract
     * @param  MetroContract  $metroContract
     * @param  ModuleServiceContract  $moduleService
     * @param  RoleServiceContract  $roleService
     * @param  PermissionContract  $permissionContract
     * @param  CarServiceContract  $carService
     */
    public function __construct(
        protected FranchiseServiceContract $franchiseService,
        protected RegionServiceContract $regionService,
        protected LegalEntityServiceContract $entityService,
        protected WorkerServiceContract $systemWorkerService,
        protected CarClassContract $carClassContract,
        protected PaymentServiceContract $paymentTypeService,
        protected DriverServiceContract $driverService,
        protected OrderServiceContract $orderService,
        protected RailwayStationContract $railwayStationContract,
        protected AirportContract $airportContract,
        protected MetroContract $metroContract,
        protected ModuleServiceContract $moduleService,
        protected RoleServiceContract $roleService,
        protected PermissionContract $permissionContract,
        protected CarServiceContract $carService,
        protected DriverStatusContract $driverStatusContract
    ) {
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function franchiseEntitiesIe(): Response|Application|ResponseFactory
    {
        return response(['entities' => $this->franchiseService->getFranchiseEntitiesIe()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function franchiseEntitiesNotIe(): Response|Application|ResponseFactory
    {
        return response(['entities' => $this->franchiseService->getFranchiseEntitiesNotIe()], 200);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function countries(): Response|ResponseFactory
    {
        return response(['countries' => $this->regionService->getAllCountries()], 200);
    }

    /**
     * @param $country_id
     * @return ResponseFactory|Response
     */
    public function regions($country_id = null): Response|ResponseFactory
    {
        return response(['regions' => $this->regionService->getRegionsByCountry($country_id)]);
    }

    /**
     * @param $region_id
     * @return ResponseFactory|Response
     */
    public function cities($region_id): Response|ResponseFactory
    {
        return response(['cities' => $this->regionService->getCitiesByRegion($region_id)], 200);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function entityTypes(): Response|ResponseFactory
    {
        return response(['entity_types' => $this->entityService->getAllEntityTypes()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function carOptions(): Response|Application|ResponseFactory
    {
        return response(['car_options' => $this->carService->getCarOptions()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function paymentTypes(): Response|Application|ResponseFactory
    {
        return response(['payment_types' => $this->paymentTypeService->getPaymentTypes()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function carClasses(): Response|Application|ResponseFactory
    {
        return response(['car_classes' => $this->carClassContract->findAll()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function driverStatuses(): Response|Application|ResponseFactory
    {
        return response(['driver_statuses' => $this->driverStatusContract->getDriverStatuses()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function orderStatuses(): Response|Application|ResponseFactory
    {
        return response(['order_statuses' => $this->orderService->getOrderStatuses()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function orderTypes(): Response|Application|ResponseFactory
    {
        return response(['order_types' => $this->orderService->getOrderTypes()], 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function railwayStations(): Response|Application|ResponseFactory
    {
        return response(json_encode(['railway_stations' => $this->railwayStationContract->findAll()], JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK), 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function metros(): Response|Application|ResponseFactory
    {
        return response(json_encode(['metros' => $this->metroContract->findAll()], JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK, 512), 200);
    }

    /**
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function airports(): Response|Application|ResponseFactory
    {
        return response(json_encode(['airports' => $this->airportContract->findAll()], JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK, 512), 200);
    }

    /**
     * @param $role_id
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function permissions($role_id): Response|Application|ResponseFactory
    {
        return response(json_encode(['permissions' => $this->permissionContract->where('role_id', '=', $role_id)->findAll()],
            JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK));
    }
}
