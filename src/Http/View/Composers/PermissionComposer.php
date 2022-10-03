<?php

declare(strict_types=1);


namespace Src\Http\View\Composers;


use Illuminate\View\View;
use Src\Repositories\Role\RoleContract;
use Src\Services\Permission\PermissionServiceContract;

/**
 * Class AdminMenuComposer
 * @package Src\Http\View\Composers
 */
class PermissionComposer
{
    /**
     * @var RoleContract
     */
    protected $permissionService;

    /**
     * RolesComposer constructor.
     * @param  PermissionServiceContract  $permissionService
     */
    public function __construct(PermissionServiceContract $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * @param  View  $view
     */
    public function compose(View $view): void
    {
        $permission = $this->permissionService->getFranchisePermissions();

        $view->with('permissions', $permission);
    }
}
