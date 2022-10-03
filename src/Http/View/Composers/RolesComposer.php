<?php

declare(strict_types=1);


namespace Src\Http\View\Composers;


use Illuminate\View\View;
use Src\Services\Role\RoleServiceContract;

/**
 * Class AdminMenuComposer
 * @package Src\Http\View\Composers
 */
class RolesComposer
{
    /**
     * RolesComposer constructor.
     * @param  RoleServiceContract  $roleService
     */
    public function __construct(protected RoleServiceContract $roleService)
    {
    }

    /**
     * @param  View  $view
     */
    public function compose(View $view): void
    {
        $roles = $this->roleService->getFranchiseRoles();

        $view->with('roles', $roles);
    }
}
