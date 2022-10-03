<?php

declare(strict_types=1);


namespace Src\Services\WorkerRole;


use ServiceEntity\BaseService;
use Src\Repositories\Permission\PermissionContract;
use Src\Repositories\Role\RoleContract;
use Src\Repositories\WorkerRole\WorkerRoleContract;

/**
 * Class WorkerRoleService
 * @package Src\Services\WorkerRole
 */
class WorkerRoleService extends BaseService implements WorkerRoleServiceContract
{
    /**
     * WorkerRoleService constructor.
     * @param  WorkerRoleContract  $workerRoleContract
     * @param  RoleContract  $roleContract
     * @param  PermissionContract  $permissionContract
     */
    public function __construct(
        protected WorkerRoleContract $workerRoleContract,
        protected RoleContract $roleContract,
        protected PermissionContract $permissionContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getRolesName($system_worker_id)
    {
        $roleNames = [];
        $roles = $this->workerRoleContract
            ->where('system_worker_id', $system_worker_id)
            ->findAll();
        foreach ($roles as $role) {
            $roleNames[] = $role->role->name;
        }
        return collect($roleNames);
    }

    /**
     * @inheritDoc
     */
    public function getRolesIds($system_worker_id)
    {
        return $this->workerRoleContract
            ->findWhere(['system_worker_id', $system_worker_id], ['system_worker_id', 'role_id'])
            ->pluck('role_id');
    }

    /**
     * @inheritDoc
     */
    public function getPermissionsName($system_worker_id)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPermissionsIds($system_worker_id)
    {
        $role_perm = $this->getRolesPermissionsId($system_worker_id);

        return collect($role_perm)->flatMap(static function ($perm) {
            return $perm['permission_ids'];
        });
    }

    /**
     * @inheritDoc
     */
    public function getRolesPermissionsId($system_worker_id)
    {
        return $this->workerRoleContract
            ->findWhere(['system_worker_id', $system_worker_id], ['system_worker_id', 'role_id', 'permission_ids'])
            ->map(static function ($roles) {
                return $roles->only(['role_id', 'permission_ids']);
            })
            ->all();
    }
}
