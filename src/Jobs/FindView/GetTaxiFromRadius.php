<?php

declare(strict_types=1);

namespace Src\Jobs\FindView;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JsonException;
use Src\Broadcasting\Broadcast\Client\ListenRadiusTaxiEvent as ClientListen;
use Src\Broadcasting\Broadcast\Worker\ListenRadiusTaxiEvent as WorkerListen;
use Src\Core\Enums\ConstDeviceType;
use Src\Core\Traits\Complex;
use Src\Http\Resources\Driver\DriverMapViewResource;
use Src\Models\Client\Client;
use Src\Models\Driver\Driver;
use Src\Models\SystemUsers\SystemWorker;
use Src\Repositories\BeforeAuthClient\BeforeAuthClientContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\ClientDriverView\ClientDriverViewContract;
use Src\Repositories\Driver\DriverContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Driver\DriverServiceContract;

/**
 * Class GetTaxiFromClientRadius
 * @package Src\Jobs
 * @property DriverContract $driverContract
 * @property ClientServiceContract $clientService
 * @property DriverServiceContract $driverService
 * @property ClientDriverViewContract $clientDriverViewContract
 */
class GetTaxiFromRadius implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Complex;

    /**
     * Create a new job instance.
     *
     * @param  string|float  $latitude
     * @param  string|float  $longitude
     * @param  Model  $client
     */
    public function __construct(protected string|float $latitude, protected string|float $longitude, protected Model $client)
    {
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function handle(): void
    {
        $this->inject(DriverContract::class, ClientServiceContract::class, DriverServiceContract::class, ClientDriverViewContract::class);

        $distance = (new Client())->getMap() === $this->client->getMap() ? config('nyt.driver_view_radius') : config('nyt.worker_driver_view_radius');

        $drivers = $this->driverContract
            ->whereHas('status', fn(Builder $status_query) => $status_query->where('status', '=', Driver::CURRENT_STATUS_IS_FREE))
            ->where('online', '=', 1)
            ->where('is_ready', '=', 1)
            ->when($this->latitude, fn($query) => $query->cordDistance($this->latitude, $this->longitude))
            ->havingRaw("distance < $distance")
            ->findAll(['driver_id', 'driver_info_id', 'lat', 'lut'])
            ->map(fn($driver) => $this->driverService->getDriverUpdatedDriverData($driver->driver_id));

        if ((new Client())->getMap() === $this->client->getMap()) {
            ClientListen::broadcast($this->client, DriverMapViewResource::collection($drivers ?: []));
            $clientContract = $this->clientService->getClientContract($this->client->getMap());
            $this->setDriverIds($drivers, $clientContract);
        }

        if ((new SystemWorker())->getMap() === $this->client->getMap()) {
            WorkerListen::broadcast($this->client, DriverMapViewResource::collection($drivers ?: []));
        }
    }

    /**
     * @param $drivers
     * @param  BeforeAuthClientContract|ClientContract  $clientContract
     * @throws JsonException
     */
    protected function setDriverIds($drivers, BeforeAuthClientContract|ClientContract $clientContract): void
    {
        $driver_ids = [];

        if ($drivers) {
            foreach ($drivers as $driver) {
                $driver_ids[] = $driver['driver_id'];
            }
        }

        $user = $clientContract
            ->with(['session_info' => fn(MorphOne $query) => $query->select(['client_session_info_id', 'clientable_id', 'clientable_type', 'device'])])
            ->find($this->client->{$this->client->getKeyName()});

        if ($user && $user->session_info && $user->session_info->device !== ConstDeviceType::BROWSER()->getValue()) {
            $this->clientDriverViewContract->updateOrCreate(
                ['clientable_type', '=', $user->getMap(), 'clientable_id', '=', get_user_id()],
                ['driver' => decode(['ids' => $driver_ids])]
            );
        }

        $this->clientDriverViewContract->updateOrCreate(
            ['clientable_type', '=', $user->getMap(), 'clientable_id', '=', $user->{$user->getKeyName()}],
            ['driver' => decode(['ids' => $driver_ids])]
        );
    }
}
