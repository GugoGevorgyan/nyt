<?php

declare(strict_types=1);

namespace App\Providers;

use App\Configures\Redis\RedisManager;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\Arr;

/**
 *
 */
class RedisProvider extends RedisServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('redis', function ($app) {
            $config = $app->make('config')->get('database.redis', []);

            return new RedisManager($app, Arr::pull($config, 'client', 'phpredis'), $config);
        });

        $this->app->bind('redis.connection', function ($app) {
            return $app['redis']->connection();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['redis', 'redis.connection'];
    }
}
