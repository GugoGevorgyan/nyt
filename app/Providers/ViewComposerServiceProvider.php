<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Request;
use Src\Http\View\Composers\AdminMenuComposer;
use Src\Http\View\Composers\AdminSuperAuthComposer;
use Src\Http\View\Composers\CustomErrorsComposer;
use Src\Http\View\Composers\GeocodeComposer;
use Src\Http\View\Composers\MaskComposer;
use Src\Http\View\Composers\ModulesComposer;
use Src\Http\View\Composers\PermissionComposer;
use Src\Http\View\Composers\RolesComposer;
use Src\Http\View\Composers\SystemWorkerAuthComposer;

/**
 * Class ViewComposerServiceProvider
 * @package App\Providers
 */
class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected bool $routeNameLogin;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routeNameLogin = Request::is('*worker/login');

        $this->apiKeyComposer();
        $this->superAdminAuthComposer();
        $this->systemWorkerAuthComposer();
        $this->customErrorsComposer();
        $this->initialWorkerComposer();
    }

    /**
     *
     */
    public function apiKeyComposer(): void
    {
        View::composer('layouts.app', GeocodeComposer::class);
        View::composer('layouts.admin-corporate', GeocodeComposer::class);
        View::composer('layouts.admin-super', GeocodeComposer::class);
        View::composer('layouts.app-mobile', GeocodeComposer::class);
        View::composer('layouts.system-worker', GeocodeComposer::class);
        View::composer('system-worker.call-center.index', GeocodeComposer::class);
        View::composer('system-worker.call-center-dispatcher.index', GeocodeComposer::class);
        View::composer('admin-super.regions', GeocodeComposer::class);
        View::composer('admin-super.cities', GeocodeComposer::class);
    }

    /**
     *
     */
    public function superAdminAuthComposer(): void
    {
        View::composer('layouts.admin-super', AdminSuperAuthComposer::class);
    }

    /**
     *
     */
    public function systemWorkerAuthComposer(): void
    {
        View::composer('layouts.system-worker', SystemWorkerAuthComposer::class);
    }

    /**
     *
     */
    public function customErrorsComposer(): void
    {
        View::composer('*', CustomErrorsComposer::class);
    }

    /**
     *
     */
    public function initialWorkerComposer(): void
    {
        if (!$this->routeNameLogin) {
            View::composer('layouts.system-worker', AdminMenuComposer::class);
            View::composer('layouts.system-worker', RolesComposer::class);
            View::composer('layouts.system-worker', PermissionComposer::class);
            View::composer('layouts.system-worker', ModulesComposer::class);
            View::composer('layouts.system-worker', MaskComposer::class);
        }
    }
}
