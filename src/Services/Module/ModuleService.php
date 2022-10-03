<?php

declare(strict_types=1);


namespace Src\Services\Module;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ServiceEntity\BaseService;
use Src\Repositories\Module\ModuleContract;
use Src\Services\WorkerRole\WorkerRoleServiceContract;

/**
 * Class ModuleService
 * @package Src\Services\Module
 */
class ModuleService extends BaseService implements ModuleServiceContract
{
    /**
     * ModuleService constructor.
     * @param  ModuleContract  $baseContract
     * @param  WorkerRoleServiceContract  $workerRoleService
     */
    public function __construct(protected ModuleContract $baseContract, protected WorkerRoleServiceContract $workerRoleService)
    {
    }

    /**
     * @return Collection
     */
    public function getModules(): Collection
    {
        return $this->baseContract->with(['roles' => fn($query) => $query->select(['*']), 'permissions' => fn($query) => $query->select(['*'])])->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getAllModules()
    {
        return $this->baseContract
            ->findWhereHas([
                'franchisee',
                fn(Builder $franchisee) => $franchisee->where('franchise_module.franchise_id', '=', \defined('FRANCHISE_ID') ? FRANCHISE_ID : 1)
            ]);
    }

    /**
     * @inheritDoc
     */
    public function getWorkerModuleIds($system_worker_id)
    {
        $roles = $this->workerRoleService->getRolesIds($system_worker_id);

        return $this->baseContract->whereHas('roles', fn(Builder $role_query) => $role_query->whereIn('role_id', $roles))
            ->findAll(['module_id'])
            ->map(fn($module) => $module->module_id);
    }

    /**
     * @param $request
     * @return JsonResponse|mixed
     */
    public function createModule($request)
    {
        $data = $request->only('name', 'description', 'default', 'alias');
        $data['slug_name'] = Str::slug($request->input('name'));
        $module = $this->baseContract->create($data);

        return response()->json([
            'module' => $module->load('roles'),
            'message' => 'Module Created Successfully!'
        ], 200);
    }

    /**
     * @param $request
     * @param $module_id
     * @return JsonResponse|mixed
     */
    public function updateModule($request, $module_id)
    {
        $module = $this->baseContract->find($module_id);
        $module->update($request->only('name', 'description', 'default', 'alias'));

        return response()->json([
            'module' => $module->fresh('roles'),
            'message' => 'Module has been successfully updated!'
        ]);
    }

    /**
     * @param $request
     * @return JsonResponse|mixed
     */
    public function destroyMultiple($request)
    {
        return $this->baseContract->deletesBy('module_id', $request->ids);
    }
}
