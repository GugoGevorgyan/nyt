<?php

declare(strict_types=1);

namespace App\Providers;

use App\Configures\BroadcastManager\TlsBroadcastManager;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Broadcasting\BroadcastServiceProvider as BaseBroadcastProvider;
use Illuminate\Contracts\Broadcasting\Broadcaster as BroadcasterContract;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastingFactory;

class BroadcastManagerProvider extends BaseBroadcastProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BroadcastManager::class, fn($app) => new TlsBroadcastManager($app));

        $this->app->singleton(BroadcasterContract::class, fn($app) => $app->make(BroadcastManager::class)->connection());

        $this->app->alias(BroadcastManager::class, BroadcastingFactory::class);
    }
}
