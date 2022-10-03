<?php

declare(strict_types=1);


namespace Src\Services\Role;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Models\Role\Role;
use Src\Repositories\Module\ModuleContract;
use Src\Repositories\Role\RoleContract;

/**
 * Class RoleService
 * @package Src\Services\WorkerRole
 */
class RoleService extends BaseService implements RoleServiceContract
{
    /**
     * @var RoleContract
     */
    protected RoleContract $roleContract;
    /**
     * @var ModuleContract
     */
    protected ModuleContract $moduleContract;

    /**
     * RoleService constructor.
     * @param  RoleContract  $baseContract
     */
    public function __construct(RoleContract $baseContract, ModuleContract $moduleContract)
    {
        $this->roleContract = $baseContract;
        $this->moduleContract = $moduleContract;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roleContract->findAll();
    }

    /**
     * @param $module_id
     * @return Collection
     */
    public function getModuleRoles($module_id): Collection
    {
        return $this->roleContract->where('module_id', '=', $module_id)->findAll();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getRolesPaginate($request)
    {
        $per_page = $request['per-page'] ?: 10;
        $page = $request['page'] ?: 1;
        $search = ($request['search'] && 'null' !== $request['search']) ? $request['search'] : '';

        return $this->roleContract
            ->with('module')
            ->when($search, fn(Builder $query) => $query
                ->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('alias', 'LIKE', '%'.$search.'%')
                ->orWhere('guard_name', 'LIKE', '%'.$search.'%')
                ->orWhereHas('module', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%'))
            )
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @return Collection
     */
    public function getFranchiseRoles(): Collection
    {
        return $this->roleContract
            ->when(\defined('FRANCHISE_ID'), fn($query) => $query
                ->whereHas('franchise_roles', fn(Builder $query) => $query->where('franchise_id', '=', FRANCHISE_ID))
            )
            ->findAll(['role_id', 'module_id', 'name', 'alias', 'description']);
    }

    /**
     * @inheritdoc
     */
    public function updateRole(array $data, Role $role): JsonResponse
    {
        $role_id = $this->roleContract->update($data);
        $this->roleContract->update($role_id, ['module_id' => $data['module_id']]);

        return response()->json([
            'role' => $role->fresh('module'),
            'message' => 'Module has been successfully updated!'
        ]);
    }
}
