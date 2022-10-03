<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Src\Core\Enums\ConstRentTimes;
use Src\Http\Controllers\Controller;
use Src\Services\Car\CarServiceContract;
use Src\Services\LegalEntity\LegalEntityServiceContract;
use Src\Services\Module\ModuleServiceContract;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\Services\Role\RoleServiceContract;
use Src\Services\Tariff\TariffServiceContract;
use Src\ServicesCrud\Tariff\TariffCrudContract;

/**
 * Class DashboardController
 * @package Src\Http\Controllers\AdminSuper
 */
class DashboardController extends Controller
{
    /**
     * @var RegionServiceContract
     */
    protected RegionServiceContract $regionService;
    /**
     * @var PaymentServiceContract
     */
    protected PaymentServiceContract $paymentTypeServiceContract;
    /**
     * @var TariffServiceContract
     */
    protected TariffServiceContract $tariffServiceContract;
    /**
     * @var TariffCrudContract
     */
    protected TariffCrudContract $tariffCrudContract;
    /**
     * @var ModuleServiceContract
     */
    protected ModuleServiceContract $moduleServiceContract;
    /**
     * @var RoleServiceContract
     */
    protected RoleServiceContract $roleServiceContract;
    /**
     * @var LegalEntityServiceContract
     */
    protected LegalEntityServiceContract $entityServiceContract;
    /**
     * @var CarServiceContract
     */
    protected CarServiceContract $carServiceContract;

    /**
     * DashboardController constructor.
     * @param  RegionServiceContract  $regionServiceContract
     * @param  PaymentServiceContract  $paymentTypeServiceContract
     * @param  TariffServiceContract  $tariffServiceContract
     * @param  TariffCrudContract  $tariffCrudContract
     * @param  ModuleServiceContract  $moduleServiceContract
     * @param  RoleServiceContract  $roleServiceContract
     * @param  LegalEntityServiceContract  $entityServiceContract
     * @param  CarServiceContract  $carServiceContract
     */
    public function __construct(
        RegionServiceContract $regionServiceContract,
        PaymentServiceContract $paymentTypeServiceContract,
        TariffServiceContract $tariffServiceContract,
        TariffCrudContract $tariffCrudContract,
        ModuleServiceContract $moduleServiceContract,
        RoleServiceContract $roleServiceContract,
        LegalEntityServiceContract $entityServiceContract,
        CarServiceContract $carServiceContract
    ) {
        $this->regionService = $regionServiceContract;
        $this->paymentTypeServiceContract = $paymentTypeServiceContract;
        $this->tariffServiceContract = $tariffServiceContract;
        $this->tariffCrudContract = $tariffCrudContract;
        $this->moduleServiceContract = $moduleServiceContract;
        $this->roleServiceContract = $roleServiceContract;
        $this->entityServiceContract = $entityServiceContract;
        $this->carServiceContract = $carServiceContract;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('admin-super.dashboard');
    }

    /**
     * @return JsonResponse
     */
    public function getTariffs()
    {
        return jsponse($this->tariffCrudContract->getTariffs(), 200, []);
    }

    /**
     * @return JsonResponse
     */
    public function getCountries()
    {
        return jsponse($this->regionService->getAllCountries(), 200);
    }

    /**
     * @param $country_id
     * @return JsonResponse
     */
    public function getRegions($country_id): JsonResponse
    {
        return jsponse($this->regionService->getRegionsByCountry($country_id), 200);
    }

    /**
     * @param $module_id
     * @return JsonResponse
     */
    public function getRoles($module_id)
    {
        return jsponse($this->roleServiceContract->getModuleRoles($module_id), 200);
    }

    /**
     * @param $region_id
     * @return JsonResponse
     */
    public function getCities($region_id)
    {
        return jsponse($this->regionService->getCitiesByRegion($region_id), 200);
    }

    /**
     * @return JsonResponse
     */
    public function getPaymentTypes()
    {
        return jsponse($this->paymentTypeServiceContract->getPaymentTypes(), 200);
    }

    /**
     * @return JsonResponse
     */
    public function getTariffTypes(): JsonResponse
    {
        return jsponse($this->tariffServiceContract->getTariffTypes(), 200);
    }

    /**
     * @return JsonResponse
     */
    public function getCarClasses()
    {
        return jsponse($this->carServiceContract->getCarClasses(), 200);
    }

    /**
     * @param  int|null  $region_id
     * @param  int|null  $country_id
     * @return JsonResponse
     */
    public function getAreas(int $region_id = null, int $country_id = null): JsonResponse
    {
        return jsponse($this->regionService->getAreas($region_id, $country_id));
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getRegionsCitiesTariffs(Request $request): JsonResponse
    {
        return jsponse($this->tariffServiceContract->getRegionsCitiesTariffs($request->city), 200);
    }

    /**
     * @return JsonResponse
     */
    public function getModules(): JsonResponse
    {
        return jsponse($this->moduleServiceContract->getModules(), 200);
    }

    /**
     * @return JsonResponse
     */
    public function getEntities(): JsonResponse
    {
        return jsponse($this->entityServiceContract->getAllEntities(), 200);
    }


    /**
     * @return array
     */
    public function getAllRentTimes(): array
    {
        return ConstRentTimes::getAll();
    }

    /**
     * @param $area_id
     * @return object|null
     */
    public function getAreaRegions($area_id)
    {
        return $this->regionService->getRegionsByArea($area_id);
    }

    /**
     * @param $car_class_id
     * @param $country_id
     * @return JsonResponse
     */
    public function getAltTariffs($car_class_id, $country_id): JsonResponse
    {
        $result = $this->tariffServiceContract->getAlternativeTariffs($car_class_id, $country_id);

        if (!$result) {
            return jsponse(['message' => 'Failed'], 500);
        }

        return jsponse(['message' => 'Success', 'alternativeTariffs' => $result], 200);
    }
}
