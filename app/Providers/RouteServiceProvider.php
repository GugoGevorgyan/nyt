<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected string $namespaceBase = 'Src\Http\Controllers';
    /**
     * @var string
     */
    protected string $namespaceApp = 'Src\Http\Controllers\App';
    /**
     * @var string
     */
    protected string $namespaceAppMobile = 'Src\Http\Controllers\AppMobile';
    /**
     * @var string
     */
    protected string $adminSuperNamespace = 'Src\Http\Controllers\AdminSuper';
    /**
     * @var string
     */
    protected string $namespaceAdminCorporate = 'Src\Http\Controllers\AdminCorporate';
    /**
     * @var string
     */
    protected string $namespaceDriverApi = 'Src\Http\Controllers\DriverApi';
    /**
     * @var string
     */
    protected string $namespaceSystemWorkerWeb = 'Src\Http\Controllers\SystemWorker';
    /**
     * @var string
     */
    protected string $namespaceSystemWorkerApi = 'Src\Http\Controllers\WorkerApi';
    /**
     * @var string
     */
    protected string $namespaceTerminalApp = 'Src\Http\Controllers\Terminal';
    /**
     * @var string
     */
    protected string $namespaceAuthApi = 'Src\Http\Controllers\AuthApi';
    /**
     * @var string
     */
    protected string $namespaceApi = 'Src\Http\Controllers\Atc';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapAppRoutes();

        $this->mapAppMobileRoutes();

        $this->mapAdminSuperRoutes();

        $this->mapAdminCorporateRoutes();

        $this->mapValidatorRoutes();

        $this->mapDriverApiRoutes();

        $this->mapSystemWorkerRoutes();

        $this->mapSystemWorkerApiRoutes();

        $this->mapTerminalAppRoutes();

        $this->mapAuthApiRoutes();

        $this->atcRoutes();

        $this->versioning();

        Route::get('refresh-csrf', function () {
            session()->regenerate();
            return response(['message' => 'ok', '_payload' => csrf_token()]);
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAppRoutes(): void
    {
        Route::middleware(['web', 'clean.numbers', 'check.client.type'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceApp)
            ->group(base_path('routes/http/app.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAppMobileRoutes(): void
    {
        Route::middleware(['api', 'clean.numbers', 'check.client.type'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->prefix('app/mobile')
            ->namespace($this->namespaceAppMobile)
            ->group(base_path('routes/http/appmob.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminSuperRoutes(): void
    {
        Route::prefix('admin/super')
            ->middleware(['web'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->adminSuperNamespace)
            ->group(base_path('routes/http/super.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminCorporateRoutes(): void
    {
        Route::prefix('admin/corporate')
            ->middleware(['web', 'clean.numbers'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceAdminCorporate)
            ->group(base_path('routes/http/corporate.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapValidatorRoutes(): void
    {
        Route::prefix('validate')
            ->namespace($this->namespaceBase)
            ->middleware(['web', 'clean.numbers'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->group(base_path('routes/http/validators.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapDriverApiRoutes(): void
    {
        Route::prefix('api/driver')
            ->middleware(['api', 'clean.numbers'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceDriverApi)
            ->group(base_path('routes/http/driver.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapSystemWorkerRoutes(): void
    {
        Route::prefix('app/worker')
            ->middleware(['web', 'clean.numbers'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceSystemWorkerWeb)
            ->group(base_path('routes/http/worker.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapSystemWorkerApiRoutes(): void
    {
        Route::prefix('api/worker')
            ->middleware(['api', 'clean.numbers'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceSystemWorkerApi)
            ->group(base_path('routes/http/workerapi.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapTerminalAppRoutes(): void
    {
        Route::prefix('app/terminal')
            ->middleware(['api', 'clean.numbers'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceTerminalApp)
            ->group(base_path('routes/http/terminal.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAuthApiRoutes(): void
    {
        Route::prefix('webhook')
            ->middleware(['api', 'clean.numbers'/*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceAuthApi)
            ->group(base_path('routes/http/webhooks.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     *These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function atcRoutes(): void
    {
        Route::prefix('atc')
            ->middleware([/*'api', 'clean.numbers'*//*, 'cache.headers:public;max_age=2628000;etag'*/])
            ->namespace($this->namespaceApi)
            ->group(base_path('routes/http/atc.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     *These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function versioning(): void
    {
        Route::prefix('rough')
            ->namespace($this->namespaceBase)
            ->middleware(['api'])
            ->group(base_path('routes/http/common.php'));
    }
}
