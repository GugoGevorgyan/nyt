<?php
declare(strict_types=1);


namespace Src\Services\Role;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;
use Src\Models\Role\Role;

/**
 * Interface RoleServiceContract
 * @package Src\Services\WorkerRole
 */
interface RoleServiceContract extends BaseContract
{
    /**
     * @return Collection
     */
    public function getFranchiseRoles(): Collection;

    /**
     * @return mixed
     */

    public function getRoles();

    /**
     * @param $request
     * @return mixed
     */
    public function getRolesPaginate($request);

    /**
     * @param $module_id
     * @return Collection
     */
    public function getModuleRoles($module_id): Collection;

    /**
     * @param  array  $data
     * @param  Role  $role
     * @return JsonResponse
     */
    public function updateRole(array $data, Role $role): JsonResponse;
}
