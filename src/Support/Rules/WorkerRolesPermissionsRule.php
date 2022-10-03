<?php

declare(strict_types=1);

namespace Src\Support\Rules;

use Illuminate\Contracts\Validation\Rule;
use Src\Repositories\Role\RoleContract;
use Src\Services\Permission\PermissionServiceContract;
use Src\Services\Role\RoleServiceContract;

use function in_array;

/**
 * Class WorkerRolesPermissionsRule
 * @package Src\Support\Rules
 */
class WorkerRolesPermissionsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     */
    public function __construct(
        protected RoleServiceContract $roleService,
        protected RoleContract $roleContract,
        protected PermissionServiceContract $permissionService
    ) {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $values
     * @return bool|null
     */
    public function passes($attribute, $values): ?bool
    {
        $values = !\is_array($values) ? (array)$values : $values;

        $roles = array_keys($values);
        $permissions = collect();

        foreach ($values as $permission) {
            if (!in_array(null, array_values($permission), true)) {
                $permissions->add(array_values($permission));
            }
        }

        $franchise_roles = $this->roleService->getFranchiseRoles()->pluck('role_id');
        $franchise_permissions = $this->permissionService->getFranchisePermissions()->pluck('permission_id')->toArray();

        /*CHECK PERMISSIONS*/
        $check_permissions = empty(array_diff($permissions->flatten()->toArray(), $franchise_permissions));

        if (!$check_permissions) {
            return false;
        }

        /*CHECK ROLES*/
        $check_roles = $this->checkRoles($roles, $franchise_roles);

        if (!$check_roles) {
            return false;
        }

        /*CHECK ROLES HAS PERMISSIONS*/
        $roles_has_permissions = $this->checkRolesHasPermissions($roles, $permissions, $values);

        if (!$roles_has_permissions) {
            return false;
        }

        return !(!$check_roles || !$check_permissions || !$roles_has_permissions);
    }

    /**
     * @param  array  $roles
     * @param $franchise_roles
     * @return bool
     */
    public function checkRoles(array $roles, $franchise_roles): bool
    {
        $check_roles = true;

        foreach ($roles as $role) {
            if (!$franchise_roles->contains($role)) {
                $check_roles = false;
            }
        }

        return $check_roles;
    }

    /**
     * @param $roles
     * @param $permissions
     * @param $values
     * @return bool|null
     */
    protected function checkRolesHasPermissions($roles, $permissions, $values): ?bool
    {
        if (($roles) && $permissions->count() > 0) {
            $datum = [];

            $this->roleContract
                ->whereIn($this->roleContract->getKeyName(), $roles)
                ->with('permissions:permission_id,role_id')
                ->findAll()
                ->pluck('permissions')
                ->flatten()
                ->flatMap(static function ($result) use ($values, &$datum) {
                    $role_id = $result->role_id;
                    $data = $values[$role_id];

                    if (in_array($result->permission_id, $data, true)) {
                        $datum[$role_id][] = $result->permission_id;
                    }
                });

            return !(!empty($datum) && !empty(array_multi_diff($datum, array_clean_null($values))));
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The validation error message.';
    }
}
