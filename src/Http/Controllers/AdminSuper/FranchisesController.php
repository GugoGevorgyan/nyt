<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminSuper\CreateFranchiseAdminRequest;
use Src\Http\Requests\AdminSuper\CreateFranchiseRequest;
use Src\Http\Requests\AdminSuper\CreateLegalEntityRequest;
use Src\Http\Requests\AdminSuper\UpdateFranchiseAdminRequest;
use Src\Http\Requests\AdminSuper\UpdateFranchiseRequest;
use Src\Repositories\DriverType\DriverTypeContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Services\Franchise\FranchiseServiceContract;
use Src\Services\LegalEntity\LegalEntityServiceContract;
use Src\Services\Module\ModuleServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\Services\Role\RoleServiceContract;

/**
 * Class FranchisesController
 * @package Src\Http\Controllers\AdminSuper
 */
class FranchisesController extends Controller
{
    /**
     * @var FranchiseServiceContract
     */
    protected FranchiseServiceContract $franchiseService;
    /**
     * @var RegionServiceContract
     */
    protected RegionServiceContract $regionService;
    /**
     * @var ModuleServiceContract
     */
    protected ModuleServiceContract $moduleService;
    /**
     * @var RoleServiceContract
     */
    protected RoleServiceContract $roleService;
    /**
     * @var LegalEntityServiceContract
     */
    protected LegalEntityServiceContract $legalEntityService;
    /**
     * @var LegalEntityServiceContract
     */
    protected LegalEntityServiceContract $entityService;
    /**
     * @var SystemWorkerContract
     */
    protected SystemWorkerContract $systemWorkerContract;
    /**
     * @var DriverTypeContract
     */
    protected DriverTypeContract $driverTypeContract;

    /**
     * FranchisesController constructor.
     * @param  FranchiseServiceContract  $franchiseService
     * @param  RegionServiceContract  $regionService
     * @param  ModuleServiceContract  $moduleService
     * @param  RoleServiceContract  $roleService
     * @param  LegalEntityServiceContract  $legalEntityService
     * @param  LegalEntityServiceContract  $entityService
     * @param  SystemWorkerContract  $systemWorkerContract
     */
    public function __construct(
        FranchiseServiceContract $franchiseService,
        RegionServiceContract $regionService,
        ModuleServiceContract $moduleService,
        RoleServiceContract $roleService,
        LegalEntityServiceContract $legalEntityService,
        LegalEntityServiceContract $entityService,
        SystemWorkerContract $systemWorkerContract,
        DriverTypeContract $driverTypeContract
    ) {
        $this->franchiseService = $franchiseService;
        $this->regionService = $regionService;
        $this->moduleService = $moduleService;
        $this->roleService = $roleService;
        $this->legalEntityService = $legalEntityService;
        $this->entityService = $entityService;
        $this->systemWorkerContract = $systemWorkerContract;
        $this->driverTypeContract = $driverTypeContract;
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function paginate(Request $request)
    {
        return $this->franchiseService->adminPaginate($request);
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('admin-super.franchises.index');
    }

    /**
     * @return Factory|View
     * @throws JsonException
     */
    public function create()
    {
        $modules = $this->moduleService->getModules();
        $countries = $this->regionService->getAllCountries();
        $entityTypes = $this->entityService->getAllEntityTypes();
        $entities = $this->legalEntityService->getAllEntities();
        $driverTypes = $this->driverTypeContract->getDriverTypes();

        return view(
            'admin-super.franchises.create',
            [
                'modules' => $modules,
                'countries' => json_encode($countries),
                'entityTypes' => json_encode($entityTypes, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'entities' => json_encode($entities, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'driverTypes' => $driverTypes
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateFranchiseRequest  $request
     * @return Response
     */
    public function store(CreateFranchiseRequest $request): Response
    {
        return ($this->franchiseService->storeFranchise($request))
            ? response(['message' => trans('messages.franchise_created')])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param $franchise_id
     * @return Application|Factory|View
     * @throws JsonException
     */
    public function edit($franchise_id)
    {
        $franchise = $this->franchiseService->getEditFranchise($franchise_id);

        if (!$franchise) {
            return \view('errors.404');
        }

        $modules = $this->moduleService->getModules();
        $countries = $this->regionService->getAllCountries();
        $entityTypes = $this->entityService->getAllEntityTypes();
        $entities = $this->legalEntityService->getAllEntities();
        $driverTypes = $this->driverTypeContract->getDriverTypes();

        return view(
            'admin-super.franchises.edit',
            [
                'modules' => $modules,
                'countries' => $countries,
                'franchise' => json_encode($franchise, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'entityTypes' => json_encode($entityTypes, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'entities' => $entities,
                'driverTypes' => $driverTypes
            ]
        );
    }

    /**
     * @param  UpdateFranchiseRequest  $request
     * @param $franchise_id
     * @return Application|ResponseFactory|Response
     */
    public function update(UpdateFranchiseRequest $request, $franchise_id)
    {
        return ($this->franchiseService->updateFranchise($request, $franchise_id))
            ? response(['message' => trans('messages.franchise_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param $franchise_id
     * @return Application|ResponseFactory|Response
     */
    public function destroy($franchise_id)
    {
        return ($this->franchiseService->deleteFranchise($franchise_id))
            ? response(['success' => true, 'message' => trans('messages.franchise_deleted')], 200)
            : response(['success' => false, 'message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param  CreateLegalEntityRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function legalEntityStore(CreateLegalEntityRequest $request)
    {
        $entity = $this->legalEntityService->adminSuperCreateEntity($request->all());

        return $entity
            ? response(['message' => trans('messages.legal_entity_created'), 'entity' => $entity])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param  CreateFranchiseAdminRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function adminStore(CreateFranchiseAdminRequest $request)
    {
        $admin = $this->franchiseService->createFranchiseAdmin($request);

        return ($admin)
            ? response(['message' => trans('messages.franchise_admin_entity_created'), 'admin' => $admin], 200)
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param  UpdateFranchiseAdminRequest  $request
     * @param $system_worker_id
     * @return Application|ResponseFactory|Response
     */
    public function adminUpdate(UpdateFranchiseAdminRequest $request, $system_worker_id)
    {
        $admin = $this->systemWorkerContract->find($system_worker_id);

        if (!$admin) {
            return response(['message' => trans('messages.something_went_wrong')], 400);
        }

        $admin = $this->franchiseService->updateFranchiseAdmin($request, $system_worker_id);

        return ($admin)
            ? response(['message' => trans('messages.franchise_admin_updated'), 'admin' => $admin], 200)
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param $system_worker_id
     * @return Application|ResponseFactory|Response
     */
    public function adminDelete($system_worker_id)
    {
        return ($this->franchiseService->deleteFranchiseAdmin($system_worker_id))
            ? response(['message' => trans('messages.franchise_admin_deleted')], 200)
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param $franchise_phone_id
     * @return Application|ResponseFactory|Response
     */
    public function phoneDelete($franchise_phone_id)
    {
        return ($this->franchiseService->deleteFranchisePhone($franchise_phone_id))
            ? response(['message' => trans('messages.franchise_phone_deleted')], 200)
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param $franchise_sub_phone_id
     * @return Application|ResponseFactory|Response
     */
    public function subPhoneDelete($franchise_sub_phone_id)
    {
        return ($this->franchiseService->deleteFranchiseSubPhone($franchise_sub_phone_id))
            ? response(['message' => trans('messages.franchise_sub_phone_deleted')], 200)
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }
}
