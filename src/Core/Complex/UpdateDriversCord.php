<?php

declare(strict_types=1);

namespace Src\Core\Complex;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Src\Core\Traits\Complex;
use Src\Events\Driver\CoordinatesUpdateEvent;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverStatus;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverCoordinate\DriverCoordinateContract;
use Src\Repositories\OrderInProcessRoad\OrderInProcessRoadContract;
use Src\Repositories\OrderOnWayRoad\OrderOnWayRoadContract;
use Src\Repositories\OrderProcess\OrderProcessContract;

/**
 * Class UpdateDriversCord
 * @package Src\Jobs
 * @method static complex(int $driver_id, float $latitude, float $longitude, int $azimuth = null, int $speed = null)
 * @property DriverContract $driverContract
 * @property OrderProcessContract $orderProcessContract
 * @property OrderInProcessRoadContract $orderInProcessRoadContract
 * @property OrderOnWayRoadContract $orderOnWayRoadContract
 */
final class UpdateDriversCord
{
    use Complex;

    /**
     * @var Carbon|null
     */
    protected ?Carbon $penultCordUpdated = null;
    /**
     * @var int|null
     */
    protected ?int $penultSpeed = null;

    /**
     * UpdateDriversCord constructor.
     * @param  int  $driverId
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  int|null  $azimuth
     * @param  int|null  $speed
     */
    public function __construct(
        protected int $driverId,
        protected float $latitude,
        protected float $longitude,
        protected null|int $azimuth = null,
        protected null|int $speed = null
    ) {
    }

    /**
     * Execute the job.
     *
     * @param  DriverContract  $driverContract
     * @param  DriverCoordinateContract  $driverCoordinateContract
     * @param  OrderProcessContract  $orderProcessContract
     * @param  OrderInProcessRoadContract  $orderInProcessRoadContract
     * @param  OrderOnWayRoadContract  $orderOnWayRoadContract
     * @return void
     */
    public function handle(
        DriverContract $driverContract,
        DriverCoordinateContract $driverCoordinateContract,
        OrderProcessContract $orderProcessContract,
        OrderInProcessRoadContract $orderInProcessRoadContract,
        OrderOnWayRoadContract $orderOnWayRoadContract
    ): void {
        $now = now();

        /*$driver_coordinate =*/
        $this->driverContract->update($this->driverId, ['lat' => $this->latitude, 'lut' => $this->longitude, 'azimuth' => $this->azimuth]);

        $coordinate = $driverCoordinateContract
            ->where('driver_id', '=', $this->driverId)
            ->where('date', '=', $now->format('Y-m-d'))
            ->exists();

        if ($coordinate) {
            $driverCoordinateContract
                ->where('driver_id', '=', $this->driverId)
                ->raw(
                    \DB::update('
                        update driver_coordinates set
                        coordinates=JSON_ARRAY_APPEND(coordinates, \'$\', CAST(\'{"lat": '.$this->latitude.', "lut": '.$this->longitude.', "date": "'.$now->format('H:i:s').'"}\' AS JSON))
                    ')
                );
        } else {
            $driverCoordinateContract->create([
                'driver_id' => $this->driverId,
                'coordinates' => [['lat' => $this->latitude, 'lut' => $this->longitude, 'date' => $now->format('H:i:s')]],
                'date' => $now->format('Y-m-d')
            ]);
        }

        $this->inOrderProcess();

        CoordinatesUpdateEvent::dispatch($this->driverId, $this->latitude, $this->longitude, $this->penultCordUpdated, $this->penultSpeed, $this->speed);
    }

    /**
     * @return void
     */
    public function inOrderProcess(): void
    {
        $driver = $this->driverContract->find($this->driverId, [$this->driverContract->getKeyName(), 'current_status_id']);

        if ($driver && $driver->current_status_id === DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE)) {
            return;
        }

        $driver->load(['current_order_shipment' => fn(HasOne $query) => $query->select(['order_shipped_driver_id', 'driver_id'])]);

        if ($driver->current_status_id === DriverStatus::getStatusId(DriverStatus::DRIVER_IN_ORDER)) {
            $this->driverInOrderUpdateData($driver, $driver->current_order_shipment->order_shipped_driver_id);
        }

        if ($driver->current_status_id === DriverStatus::getStatusId(DriverStatus::DRIVER_ON_WAY)) {
            $this->driverOnWayUpdateData($driver, $driver->current_order_shipment->order_shipped_driver_id);
        }
    }

    /**
     * @param  Driver  $driver
     * @param $shipment_id
     * @return void
     */
    protected function driverInOrderUpdateData(Driver $driver, $shipment_id): void
    {
        $updated = f_now();

        if ($driver->has('order_in_process_roads')->exists()) {
            $driver->load([
                'order_in_process_roads' => fn(HasManyThrough $q_process_road) => $q_process_road
                    ->where('shipment_driver_id', '=', $shipment_id)
                    ->where('selected', '=', 1)
                    ->select(['shipment_driver_id', 'real_road', 'order_in_process_road_id'])
                    ->first(),
                'process' => fn(HasOneThrough $query) => $query
                    ->select(['order_processes.cord_updated', 'order_processes.speed', 'order_shipped_id'])
            ]);

            $order_in_process_road = $driver->order_in_process_roads->first();
            $new_cord = $order_in_process_road->real_road;
            $new_cord[] = ['lat' => $this->latitude, 'lut' => $this->longitude, 'date' => $updated];

            $this->penultCordUpdated = $driver->process->cord_updated;
            $this->penultSpeed = $driver->process->speed;

            $order_in_process_road->update(['real_road' => $new_cord]);
            $driver->process::updateOrCreate(['order_shipped_id' => $shipment_id,], ['cord_updated' => $updated]);
        } else {
            $this->penultCordUpdated = now();

            $this->orderInProcessRoadContract->create([
                'real_road' => [['lat' => $this->latitude, 'lut' => $this->longitude, 'date' => $updated]],
                'shipment_driver_id' => $shipment_id,
                'selected' => 1
            ]);
            $this->orderProcessContract->create(['cord_updated' => $this->penultCordUpdated]);
        }
    }

    /**
     * @param  Driver  $driver
     * @param $shipment_id
     * @return void
     */
    protected function driverOnWayUpdateData(Driver $driver, $shipment_id): void
    {
        $updated = f_now();

        if ($driver::has('order_on_way_roads')->exists()) {
            $driver->load([
                'order_on_way_roads' => fn(HasManyThrough $q_on_way) => $q_on_way
                    ->where('shipment_driver_id', '=', $shipment_id)
                    ->select(['order_on_way_road_id', 'shipment_driver_id', 'route', 'real_road']),
                'process' => fn(HasOneThrough $query) => $query
                    ->select(['order_processes.cord_updated', 'order_processes.speed', 'order_shipped_id'])
            ]);
            $order_on_way_road = $driver->order_on_way_roads->first();

            $new_cord = $order_on_way_road->real_road;
            $new_cord[] = [
                'lat' => $this->latitude,
                'lut' => $this->longitude,
                'date' => $updated
            ];

            $this->penultCordUpdated = $driver->process->cord_updated;
            $this->penultSpeed = $driver->process->speed;
            $order_on_way_road->update(['real_road' => $new_cord]);
            $driver->process::updateOrCreate(['order_shipped_id' => $shipment_id,], ['cord_updated' => $updated]);
        } else {
            $this->orderOnWayRoadContract->create([
                'real_road' => [['lat' => $this->latitude, 'lut' => $this->longitude, 'date' => $updated]],
                'shipment_driver_id' => $shipment_id,
                'selected' => 1
            ]);

            $this->orderProcessContract->create(['cord_updated' => $this->penultCordUpdated]);
        }
    }
}
