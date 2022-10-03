<?php

declare(strict_types=1);

namespace App\Providers;

use Barryvdh\Debugbar\LaravelDebugbar;
use Debugbar;
use Dionera\BeanstalkdUI\BeanstalkdUIServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\HorizonApplicationServiceProvider;
use Laravel\Telescope\TelescopeApplicationServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;
use RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace;
use RenatoMarinho\LaravelPageSpeed\Middleware\DeferJavascript;
use RenatoMarinho\LaravelPageSpeed\Middleware\ElideAttributes;
use RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss;
use RenatoMarinho\LaravelPageSpeed\Middleware\InsertDNSPrefetch;
use RenatoMarinho\LaravelPageSpeed\Middleware\RemoveComments;
use RenatoMarinho\LaravelPageSpeed\Middleware\RemoveQuotes;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Src\Core\Socket\Commands\StartSocket;
use Src\Http\Middleware\App\AddHttp2ServerPush;

use function define;
use function defined;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $environment = config('app.env');
        $debug = config('app.debug');

        if ('local' === $environment && class_exists(TelescopeApplicationServiceProvider::class) && $debug) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        if ($debug && 'local' === $environment && class_exists(HorizonApplicationServiceProvider::class)) {
            $this->app->register(HorizonServiceProvider::class);
        }

        if ('production' === $environment && class_exists(LaravelDebugbar::class) && !config('app.debug')) {
            Debugbar::disable();
        }

        if (!$debug && 'production' !== $environment && class_exists(BeanstalkdUIServiceProvider::class)) {
            $this->app->register(BeanstalkdUIServiceProvider::class);
        }

        !defined('DS') ? define('DS', DIRECTORY_SEPARATOR) : null;

        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @param  Kernel  $kernel
     * @return void
     */
    public function boot(Kernel $kernel): void
    {
        $this->commands([StartSocket::class]);

        JsonResource::withoutWrapping();
        JsonResource::wrap(null);

        if (app()->environment('production')) {
            $this->initProdMiddleware($kernel);
        }
    }

    /**
     * @param  Kernel  $kernel
     */
    protected function initProdMiddleware(Kernel $kernel): void
    {
        $kernel->prependMiddleware(AddHttp2ServerPush::class);
        $kernel->prependMiddleware(RemoveComments::class);
        $kernel->prependMiddleware(RemoveQuotes::class);
        $kernel->prependMiddleware(InsertDNSPrefetch::class);
        $kernel->prependMiddleware(CollapseWhitespace::class);

        $kernel->prependMiddlewareToGroup('web', CacheResponse::class);
        $kernel->prependMiddlewareToGroup('web', ElideAttributes::class);
        $kernel->prependMiddlewareToGroup('web', DeferJavascript::class);
        $kernel->prependMiddlewareToGroup('web', InlineCss::class);

        $kernel->prependMiddlewareToGroup('api', CacheResponse::class);
    }
}
