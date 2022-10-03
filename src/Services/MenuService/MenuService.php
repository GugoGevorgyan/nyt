<?php

declare(strict_types=1);


namespace Src\Services\MenuService;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Repositories\Menu\MenuContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * Class MenuService
 * @package Src\Services\MenuService
 */
class MenuService extends BaseService implements MenuServiceContract
{
    /**
     * Create the event listener.
     *
     * @param  SystemWorkerContract  $workerContract
     * @param  MenuContract  $menuContract
     */
    public function __construct(protected SystemWorkerContract $workerContract, protected MenuContract $menuContract)
    {
    }

    /**
     * @param  BelongsToMany  $module_query
     * @return BelongsToMany
     */
    private static function getModulesRelations(BelongsToMany $module_query): BelongsToMany
    {
        return $module_query->select([
            'modules.module_id',
            'name',
            'description',
            'icon',
            'franchise_id',
            'role_ids'
        ])->with([
            'permissions' => fn(HasManyThrough $permission_query) => $permission_query->select([
                'permissions.permission_id',
                'permissions.role_id',
                'permissions.name',
                'permissions.description'
            ]),
            'route' => fn(BelongsTo $route_query) => $route_query->select(['route_id', 'name', 'type', 'url'])
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getMenu(): ?Collection
    {
        $worker = $this->workerContract
            ->with(['roles' => fn($query) => $query->select(['*'])])
            ->find(get_user_id(), ['system_worker_id', 'is_admin']);

        if (!$worker) {
            return null;
        }

        $with_data = [
            'roles' => fn(BelongsToMany $q) => $q->with('permissions'),
            'route' => fn($query) => $query->select(['*']),
            'parent' => fn($query) => $query->select(['*']),
            'child' => fn($query) => $query->with(['route'])->select(['*']),
        ];

        if ($worker->is_admin) {
            return $this->menuContract
                ->with($with_data)
                ->findAll()
                ->sortBy('title');
        }

        return $this->menuContract
            ->with($with_data)
            ->whereHas('roles', static function (Builder $query) use ($worker) {
                $query->where('roles.role_id');
                foreach ($worker->roles as $role) {
                    $query->orWhere('roles.role_id', '=', $role->role_id);
                }
            })
            ->findAll()
            ->sortBy('title');
    }
}
