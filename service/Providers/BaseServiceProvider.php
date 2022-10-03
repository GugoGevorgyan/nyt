<?php

declare(strict_types=1);

namespace ServiceEntity\Providers;

use Illuminate\Support\ServiceProvider;
use ServiceEntity\BaseService;
use ServiceEntity\Contract\BaseContract;
use ServiceEntity\GeoIP\Console\Clear;
use ServiceEntity\GeoIP\Console\Update;
use ServiceEntity\GeoIP\GeoIP;

/**
 * Class BaseServiceProvider
 * @package ServiceEntity\Providers
 */
class BaseServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register(): void
    {
        $this->app->bindIf(BaseContract::class, BaseService::class);

        $this->bindGeoIP();
    }

    /**
     *
     */
    protected function bindGeoIP(): void
    {
        $this->app->singleton('geoip', fn($app) => new GeoIP($app->config->get('geoip', []), $app['cache']));

        if ($this->app->runningInConsole()) {
            $this->registerResources();
            $this->registerGeoIpCommands();
        }

        $this->mergeConfigFrom(__DIR__.'/../../config/geoip.php', 'geoip');
    }

    /**
     * Register resources.
     *
     * @return void
     */
    public function registerResources(): void
    {
        $this->publishes([__DIR__.'/../../config/geoip.php' => config_path('geoip.php')], 'config');
    }

    /**
     * Register commands.
     *
     * @return void
     */
    public function registerGeoIpCommands(): void
    {
        $this->commands([
            Update::class,
            Clear::class,
        ]);
    }

}
