<?php

declare(strict_types=1);


namespace Src\Services\Permission;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Models\Role\Permission;
use Src\Repositories\Permission\PermissionContract;
use Src\Services\Role\RoleServiceContract;

/**
 * Class PermissionService
 * @package Src\Services\Permission
 */
class PermissionService extends BaseService implements PermissionServiceContract
{
    /**
     * @param  PermissionContract  $baseContract
     * @param  RoleServiceContract  $roleService
     */
    public function __construct(protected PermissionContract $baseContract, protected RoleServiceContract $roleService)
    {
    }

    /**
     * @inheritDoc
     */
    public function getFranchisePermissions()
    {
        return $this->roleService
            ->getFranchiseRoles()
            ->load('permissions')
            ->pluck('permissions')
            ->flatMap(static function (Collection $values) {
                return $values->map(static function (Permission $value) {
                    return $value->only('permission_id', 'role_id', 'name', 'alias', 'description');
                });
            });
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getPermissionsPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] ?: 10;
        $page = $request['page'] ?: 1;
        $search = ($request['search'] && 'null' !== $request['search']) ? $request['search'] : '';

        return $this->baseContract
            ->with('role')
            ->when($search, static function (Builder $query) use ($search) {
                return $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('alias', 'LIKE', '%'.$search.'%')
                    ->orWhere('guard_name', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('role', function ($q) use ($search) {
                        return $q->where('name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->paginate($per_page, ['*'], 'page', $page);
    }
}
