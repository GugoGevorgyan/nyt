<?php

declare(strict_types=1);

namespace App\Providers;

use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use Broadcast;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Pusher\Pusher;
use Pusher\PusherException;
use Src\Core\Additional\Guzzle;
use Src\Core\Enums\ConstChannels;
use Src\Core\Socket\RouterHandler;

/**
 * Class BroadcastServiceProvider
 * @package App\Providers
 */
class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        WebSocketsRouter::webSocket('nyt-manual-channel', RouterHandler::class);

        $request = app(Request::class);
        $channel_name = $request->channel_name ?: '';

        if (str_contains($channel_name, ConstChannels::client_api()->getValue())) {
            Broadcast::routes(['middleware' => ['api', 'auth:clients_api']]);
            require base_path('routes/channel/client-api.php');
        }

        if (str_contains($channel_name, ConstChannels::driver_api()->getValue())) {
            Broadcast::routes(['middleware' => ['api', 'auth:drivers_api']]);
            require base_path('routes/channel/driver-api.php');
        }

        if (str_contains($channel_name, ConstChannels::client_web()->getValue())) {
            Broadcast::routes(['middleware' => ['web', 'auth:clients_web']]);
            require base_path('routes/channel/client-web.php');
        }

        if (str_contains($channel_name, ConstChannels::before_client_web()->getValue())) {
            Broadcast::routes(['middleware' => ['web', 'auth:before_clients_web']]);
            require base_path('routes/channel/before-client-web.php');
        }

        if (str_contains($channel_name, ConstChannels::worker_api()->getValue())) {
            Broadcast::routes(['middleware' => ['api', 'auth:system_workers_api']]);
            require base_path('routes/channel/workerapi.php');
        }

        if (str_contains($channel_name, ConstChannels::worker_web()->getValue())) {
            Broadcast::routes(['middleware' => ['web', 'auth:system_workers_web']]);
            require base_path('routes/channel/worker-web.php');
        }

        if (str_contains($channel_name, ConstChannels::personal_api()->getValue())) {
            Broadcast::routes(['middleware' => ['api', 'auth:api_clients']]);
            require base_path('routes/channel/api-client-channels.php');
        }

        if (str_contains($channel_name, ConstChannels::admin_corporate_api()->getValue())) {
            Broadcast::routes(['middleware' => ['api', 'auth:api_clients']]);
            require base_path('routes/channel/admin-corporate-api.php');
        }

        if (str_contains($channel_name, ConstChannels::admin_corporate_web()->getValue())) {
            Broadcast::routes(['middleware' => ['web', 'auth:admin_corporate_web']]);
            require base_path('routes/channel/admin-corporate-web.php');
        }
    }
}
