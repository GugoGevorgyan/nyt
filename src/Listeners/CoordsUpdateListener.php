<?php

declare(strict_types=1);

namespace Src\Listeners;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JsonException;
use Src\Broadcasting\Broadcast\Client\ListenRadiusTaxiEvent;
use Src\Broadcasting\Broadcast\Client\ListenTaxiPositionEvent;
use Src\Events\Driver\CoordinatesUpdateEvent;
use Src\Http\Resources\Driver\DriverMapViewResource;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\ClientDriverView\ClientDriverViewContract;
use Src\Repositories\Driver\DriverContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * Class CoordsUpdateListener
 * @package Src\Listeners
 */
class CoordsUpdateListener
{
    /**
     * @var string|int
     */
    protected string|int $driverId;

    /**
     * @param  DriverContract  $driverContract
     * @param  ClientContract  $clientContract
     * @param  ClientDriverViewContract  $clientViewContract
     * @param  ClientServiceContract  $clientService
     * @param  DriverServiceContract  $driverService
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected ClientContract $clientContract,
        protected ClientDriverViewContract $clientViewContract,
        protected ClientServiceContract $clientService,
        protected DriverServiceContract $driverService
    ) {
    }

    /**
     * Handle the event.
     *
     * @param  CoordinatesUpdateEvent  $event
     * @return void
     * @throws JsonException
     */
    public function handle(CoordinatesUpdateEvent $event): void
    {
        $this->driverId = $event->driver_id;

        $driver = $this->getDriverClientView();
        $driver_coordinate = ['lat' => $driver->lat, 'lut' => $driver->lut];

        if ($this->isOrderRoadDriver()) {
            return;
        }

        if ($driver->client_driver_view->count() > 0) {
            $driver->client_driver_view->flatMap(fn(Model $data) => $this->getDriverInitialLesson($data, $driver_coordinate, $event));
        }

        $this->getDriversInitialUnListen($driver);
    }

    /**
     * @return mixed
     */
    protected function getDriverClientView(): mixed
    {
        return $this->driverContract
            ->with([
                'client_driver_view' => fn(HasManyJson $driver_view_query) => $driver_view_query->select([
                    'driver',
                    'clientable_id',
                    'clientable_type',
                    'client_driver_view_id'
                ])
            ])
            ->find($this->driverId, ['driver_id', 'current_status_id', 'lat', 'lut']);
    }

    /**
     * @return bool
     */
    protected function isOrderRoadDriver(): bool
    {
        $road_client_view = $this->taxiHasOnRoadClient();

        if ($road_client_view && $road_client_view->current_order) {
            $driver = $this->driverService->getDriverUpdatedDriverData($this->driverId);
            $client = $this->clientService->getOrderedClientData($road_client_view->current_order->order_id, ['client_id', 'phone']);
            ListenTaxiPositionEvent::broadcast($client, new DriverMapViewResource($driver), 'updated');

            return true;
        }

        return false;
    }

    /**
     * @return object|null
     */
    protected function taxiHasOnRoadClient(): ?object
    {
        return $this->driverContract
            ->with(['current_order' => fn($query) => $query->select(['orders.order_id', 'client_id'])])
            ->find($this->driverId, ['driver_id', 'lat', 'lut', 'azimuth']);
    }

    /**
     * @param $data
     * @param $driver_coordinate
     * @param $event
     * @return void
     * @throws JsonException
     */
    protected function getDriverInitialLesson($data, $driver_coordinate, $event): void
    {
        $is_auth_client = $this->clientService->clientIsAuth($data->clientable_type);
        $client_contract = $this->clientService->getClientContract($data->clientable_type);
        $client_coordinate = $this->clientService->getCorrectCoordinate($data->clientable_id, $data->clientable_type);

        if (!$client_coordinate) {
            return;
        }

        $distance = distance_cords($client_coordinate['lat'], $client_coordinate['lut'], $driver_coordinate['lat'], $driver_coordinate['lut'], 'm');
        $client = $client_contract->find($data->clientable_id, [$client_contract->getKeyName(), $is_auth_client ? 'phone' : 'hash']);
        $taxi = $this->driverService->getDriverUpdatedDriverData($this->driverId);

        if ($distance > config('nyt.driver_view_radius')) {
            $this->getNotMatchingInInitialDistance($data, $event->driver_id);
            ListenRadiusTaxiEvent::broadcast($client, DriverMapViewResource::collection([$taxi]), 'deleted')->toOthers();
        } else {
            ListenRadiusTaxiEvent::broadcast($client, DriverMapViewResource::collection([$taxi]), 'updated')->toOthers();
        }
    }

    /**
     * @param $data
     * @param $driver_id
     * @throws JsonException
     */
    protected function getNotMatchingInInitialDistance($data, $driver_id): void
    {
        $long = $this->clientViewContract
            ->findWhere(['client_driver_view_id', '=', $data->client_driver_view_id], ['client_driver_view_id', 'driver']);

        foreach ($long as $l_exp) {
            if (false !== ($key = array_search($driver_id, $l_exp->driver['ids'], true))) {
                $datum = $l_exp->driver['ids'];
                unset($datum[$key]);

                $this->clientViewContract
                    ->updateOrCreate(
                        ['clientable_id', '=', $data->clientable_id, 'clientable_type', '=', $data->clientable_type],
                        [
                            'clientable_id' => $data->clientable_id,
                            'driver' => decode(['ids' => array_values($datum)])
                        ]
                    );
            }
        }
    }

    /**
     * @param $driver
     * @return void
     * @throws JsonException
     */
    protected function getDriversInitialUnListen($driver): void
    {
        $clients = $this->clientViewContract
            ->whereHas('client', fn(Builder $query) => $query->has('initial_order_data'))
//            ->whereHasMorph('client', [Client::class], fn($query) => $query->has('initial_order_data'))// @TODO SLOW QUERY
            ->whereJsonNotIn('driver->ids', $driver->driver_id)
            ->findAll(['client_driver_view_id', 'clientable_id', 'clientable_type', 'driver']);

        $taxi = $this->driverService->getDriverUpdatedDriverData($this->driverId);

        foreach ($clients as $client) {
            $client_coordinate = $this->clientService->getCorrectCoordinate($client->clientable_id, $client->clientable_type);

            if (!$client_coordinate) {
                return;
            }

            $distance = distance_cords($client_coordinate['lat'], $client_coordinate['lut'], $driver->lat, $driver->lut, 'm');

            if ($distance <= config('nyt.driver_view_radius')) {
                $driver_ids = $client->driver['ids'];
                $driver_ids[] = $driver->driver_id;

                $this->clientViewContract
                    ->updateOrCreate(
                        ['clientable_id' => $client->clientable_id, 'clientable_type' => $client->clientable_type],
                        [
                            'driver' => decode(['ids' => array_unique(array_values($driver_ids))]),
                            'clientable_id' => $client->clientable_id,
                            'clientable_type' => $client->clientable_type
                        ]
                    );

                $new_lesson_client = $this->clientContract->find($client->clientable_id);
                ListenRadiusTaxiEvent::broadcast($new_lesson_client, DriverMapViewResource::collection([$taxi]), 'updated')->toOthers();
            }
        }
    }
}
