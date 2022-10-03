<?php

declare(strict_types=1);


namespace Src\Http\View\Composers;


use Illuminate\View\View;
use Src\Services\MenuService\MenuServiceContract;

/**
 * Class AdminMenuComposer
 * @package Src\Http\View\Composers
 */
class AdminMenuComposer
{
    /**
     * AdminMenuComposer constructor.
     * @param  MenuServiceContract  $menuService
     */
    public function __construct(protected MenuServiceContract $menuService)
    {
    }

    /**
     * @param  View  $view
     */
    public function compose(View $view): void
    {
        $menu = $this->menuService->getMenu();
        $view->with('menu', $menu);
    }
}
