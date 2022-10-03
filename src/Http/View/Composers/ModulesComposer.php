<?php

declare(strict_types=1);


namespace Src\Http\View\Composers;


use Illuminate\View\View;
use Src\Services\Module\ModuleServiceContract;

/**
 * Class ModuleComposer
 * @package Src\Http\View\Composers
 */
class ModulesComposer
{
    /**
     * ModulesComposer constructor.
     * @param  ModuleServiceContract  $moduleService
     */
    public function __construct(protected ModuleServiceContract $moduleService)
    {
    }

    /**
     * @param  View  $view
     */
    public function compose(View $view): void
    {
        $view->with('modules', $this->moduleService->getAllModules());
    }
}
