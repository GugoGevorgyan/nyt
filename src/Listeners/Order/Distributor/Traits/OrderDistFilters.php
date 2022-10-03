<?php

declare(strict_types=1);


namespace Src\Listeners\Order\Distributor\Traits;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use JsonException;
use Src\Core\Enums\ConstDriverType;
use Src\Exceptions\Lexcept;
use Src\Models\Driver\Driver;

/**
 * Trait OrderDistFilters
 * @package Src\Core\Traits
 */
trait OrderDistFilters
{
    /**
     * @param $drivers
     * @param $s_distance
     * @param $s_duration
     * @return object|null
     * @throws Lexcept
     * @throws JsonException
     */
    public function getDriversFilter($drivers, $s_distance, $s_duration): ?object
    {
        if ($drivers->count() > 1) {
            $this->filterByShipped($drivers);

            return $this->multiFilter($drivers, $s_distance, $s_duration);
        }

        $first_driver = $drivers->first();

        return $this->singleFilter($first_driver);
    }

    /**
     * @param  Collection  $drivers
     * @return void
     * @ref $drivers
     */
    protected function filterByShipped(&$drivers): void
    {
        $shipped_data = [];

        foreach ($drivers as $key_driver => $driver) {
            if (!$driver->orders_shipment) {
                array_swap_assoc(0, $key_driver, $drivers);
                break;
            }

            foreach ($driver->orders_shipment as $shipped) {
                $shipped_data[] = ['driver_id' => $driver->driver_id, 'shipped_status' => $shipped->status_id, 'shipped_time' => $shipped->created_at];
            }
        }

        $_by_date_driver = null;

        foreach ($shipped_data as $key_shipped => $shipped) {
            if (!$prev = $shipped_data[$key_shipped - 1] ?? null) {
                continue;
            }

            if ($shipped && Carbon::parse($shipped['shipped_time']) < Carbon::parse($prev['shipped_time'])) {
                $_by_date_driver = $shipped['driver_id'];
            }
        }

        foreach ($drivers as $key => $driver) {
            if ($driver->driver_id === $_by_date_driver) {
                array_swap_assoc(0, $key, $drivers);
            }
        }
    }

    /**
     * @param $drivers
     * @param $s_distance
     * @param $s_duration
     * @return mixed
     * @throws Lexcept|JsonException
     */
    protected function multiFilter($drivers, $s_distance, $s_duration): mixed
    {
        $filtered_drivers = [];

        foreach ($drivers as $key => $driver) {
            if (0 === $key) {
                continue;
            }

            $filtered_drivers[] = $driver;
        }

        $first_driver = $drivers->first();

        $distance = $this->geoService->roadCalculation($this->payload->order->from_coordinates, ['lat' => $first_driver->lat, 'lut' => $first_driver->lut]);

        if (!$this->distinctPreorder($first_driver, $distance)) {
            $this->rejectDriverCurrentCircle($first_driver->driver_id);
            array_walk($filtered_drivers, static fn($item) => $item->driver_id !== $first_driver->driver_id);
            return $this->getDriversFilter(collect($filtered_drivers), $s_distance + 0.3, $s_duration + 5);
        }

        if ($distance['distance'] <= $s_distance || $distance['duration'] <= $s_duration) {
            $first_driver->road_distance = $distance['distance'];
            $first_driver->road_duration = $distance['duration'];
            $first_driver->road_point = $distance['points'];

            return $first_driver;
        }

        return $this->getDriversFilter(collect($filtered_drivers), $s_distance + 0.2, $s_duration + 5);
    }

    /**
     * @param  Driver  $driver
     * @param  array  $distance
     * @return bool
     */
    protected function distinctPreorder(Driver $driver, array $distance): bool
    {
        $initial = null;
        $result = true;

        if ($driver->common_orders):
            if (!empty($this->app->payload->order->to_coordinates)) {
                $initial = $this->app->initialOrderContract
                    ->where('order_id', '=', $this->app->payload->order->order_id)
                    ->findFirst(['order_id', 'order_initial_data_id', 'distance', 'duration']);
            }

            foreach ($driver->common_orders as $preorder) {
                if ($initial && ($initial->duration + $distance['duration']) >= (now()->diffInMinutes(Carbon::parse($preorder->preorder->distribution_start)) + 16)) {
                    $result = false;
                    break;
                }

                if (!$initial && ($distance['duration'] + 10) >= (now()->diffInMinutes(Carbon::parse($preorder->preorder->distribution_start)) + 10)) {
                    $result = false;
                    break;
                }

                if (16 > now()->diffInMinutes(Carbon::parse($preorder->preorder->distribution_start))) {
                    $result = false;
                    break;
                }
            }
        endif;

        return $result;
    }

    /**
     * @param $driver_id
     * @param  bool  $rejected
     */
    protected function rejectDriverCurrentCircle($driver_id, bool $rejected = true): void
    {
        foreach ($this->selectedDrivers as $key => $driver) {
            if ($driver->driver_id === $driver_id) {
                unset($this->selectedDrivers[$key]);
            }
        }

        $rejected ? $this->rejectedDrivers[] = $driver_id : null;
    }

    /**
     * @param $driver
     * @return mixed
     */
    protected function singleFilter($driver): mixed
    {
        $distance = $this->geoService->roadCalculation(['lat' => $driver->lat, 'lut' => $driver->lut], $this->payload->order->from_coordinates);

        if (!$this->distinctPreorder($driver, $distance)) {
            $this->rejectDriverCurrentCircle($driver->driver_id);
            return null;
        }

        if ($distance['distance'] <= config('nyt.allowed_distance_driver') || $distance['duration'] <= config('nyt.allowed_duration_driver')) {
            $driver->road_distance = $distance['distance'];
            $driver->road_duration = $distance['duration'];
            $driver->road_point = $distance['points'];

            return $driver;
        }

        return null;
    }

    /**
     * @param  bool  $twist
     */
    protected function setType(bool $twist = false): void
    {
        switch ($this->driverType) {
            case null:
                $this->driverType[] = $twist ? ConstDriverType::TENANT()->getValue() : ConstDriverType::CORPORATE()->getValue();
                break;
            case ConstDriverType::CORPORATE()->getValue():
                $this->driverType[] = $twist ? ConstDriverType::ROLL()->getValue() : ConstDriverType::TENANT()->getValue();
                break;
            case ConstDriverType::TENANT()->getValue():
                $this->driverType[] = $twist ? ConstDriverType::AGGREGATOR()->getValue() : ConstDriverType::ROLL()->getValue();
                break;
            case ConstDriverType::ROLL()->getValue():
                $this->driverType[] = $twist ? ConstDriverType::CORPORATE()->getValue() : ConstDriverType::AGGREGATOR()->getValue();
                break;
            default:
        }
    }
}
