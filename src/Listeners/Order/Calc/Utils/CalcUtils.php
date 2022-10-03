<?php

declare(strict_types=1);


namespace Src\Listeners\Order\Calc\Utils;


use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Src\Core\Enums\ConstRedis;
use Src\Events\Driver\CoordinatesUpdateEvent;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\OrderProcess;
use Src\Models\Order\OrderStatus;
use Src\Models\Tariff\TariffPriceType;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * Class CalcUtils
 * @package Src\Listeners\Order\Calc\Utils
 */
abstract class CalcUtils
{
    use RealTariffDetect;

    /**
     * @var Driver|null
     */
    public ?Driver $driver = null;
    /**
     * @var array|null
     */
    public ?array $tariffs = [];
    /**
     * @var array{km_hr: string|int, distance_meter: int|string, duration_second: int|string}
     */
    public array $matrix = ['km_hr' => 0, 'distance_meter' => 0, 'duration_second' => 0];
    /**
     * @var array|null
     */
    public ?array $cord;
    /**
     * @var array|null
     */
    protected ?array $passedData = [];
    /**
     * @var int|null
     */
    protected ?int $driverId;
    /**
     * @var Carbon|null
     */
    protected ?Carbon $penultCordUpdated;
    /**
     * @var int|null
     */
    protected ?int $penultSpeed;
    /**
     * @var int|null
     */
    protected ?int $speed;

    /**
     * @return OrderProcess|null
     */
    protected function getProcess(): ?OrderProcess
    {
        $redis_key = ConstRedis::order_calc_data($this->driver->current_order->order_id);
        $calc_data = $this->redis->hmget($redis_key, ['stages', 'road']);

        if ($calc_data && $calc_data[0]) {
            $this->driver->load(['process' => $this->processLoad()]);
            $this->driver->current_order_stages = igbinary_unserialize($calc_data[0]);
            $this->driver->order_in_process_road = igbinary_unserialize($calc_data[1]);
        } else {
            $this->driver->load([
                'process' => $this->processLoad(),
                'current_order_stages' => fn(HasManyDeep $q_order_stage) => $q_order_stage->select([
                    'order_stages_cord.order_stage_cord_id',
                    'order_stages_cord.order_id',
                    'order_stages_cord.started'
                ]),
                'order_in_process_road' => fn(HasOneThrough $query) => $query->select([
                    'order_in_process_road_id',
                    'shipment_driver_id',
                    'distance',
                    'duration'
                ])
            ]);

            $this->redis->hmset($redis_key, [
                'stages' => igbinary_serialize($this->driver->current_order_stages),
                'road' => igbinary_serialize($this->driver->order_in_process_road)
            ]);
        }

        return $this->driver->process;
    }

    /**
     * @return Closure
     */
    protected function processLoad(): Closure
    {
        return static fn(HasOneThrough $query) => $query->select([
            'order_process_id',
            'order_shipped_id',

            'price',
            'calculate_price',
            'increment_price',
            'pause_price',
            'total_price',
            'sitting_price',
            'options_price',
            'waiting_price',

            'price_passed',
            'cord_updated',
            'distance_traveled',
            'travel_time',
            'pause_time',
        ]);
    }

    /**
     * @param  CoordinatesUpdateEvent  $event
     */
    protected function injectData(CoordinatesUpdateEvent $event): void
    {
        $this->driverId = $event->driver_id;
        $this->cord = ['lat' => $event->latitude, 'lut' => $event->longitude];
        $this->penultCordUpdated = $event->penultCordUpdated;
        $this->penultSpeed = $event->penultSpeed;
        $this->speed = $event->speed;
    }

    /**
     * @param  Model  $road  @todo fix this
     */
    protected function getInitialTariffs(Model $road): void
    {
        $redis_key = ConstRedis::order_calc_data($this->driver->current_order->order_id);
        $calc_data = $this->redis->hmget($redis_key, ['initial_tariff', 'second_tariff']);

        if ($calc_data && $calc_data[0]) {
            $initial_tariff = igbinary_unserialize($calc_data[0]);
            $second_tariff = igbinary_unserialize($calc_data[1]);
        } else {
            $tariff = $road->load(['order_initial_tariff', 'order_second_tariff']);
            $initial_tariff = $tariff->order_initial_tariff;
            $second_tariff = $tariff->order_second_tariff;

            $this->redis->hmset($redis_key, [
                'initial_tariff' => igbinary_serialize($initial_tariff),
                'second_tariff' => igbinary_serialize($second_tariff)
            ]);
        }

        $this->tariffs = compact('initial_tariff', 'second_tariff');
    }

    /**
     * @return Driver|null
     */
    protected function getDriverInOrderData(): ?Driver
    {
        if ($this->driver && $saved_driver = $this->redis->hmget(ConstRedis::order_calc_data($this->driver->current_order->order_id), ['driver'])[0] ?? null) {
            $this->driver = igbinary_unserialize($saved_driver);
        } else {
            $this->driver = $this->driverContract
                ->where(fn($query) => $query
                    ->where('current_status_id', '=', DriverStatus::getStatusId(DriverStatus::DRIVER_IN_ORDER))
                    ->orWhere('current_status_id', '=', DriverStatus::getStatusId(DriverStatus::DRIVER_ON_WAY))
                )
                ->where('driver_id', '=', $this->driverId)
                ->with([
                    'current_order' => fn(HasManyDeep $q_order) => $q_order
                        ->where('orders.status_id', '!=', OrderStatus::ORDER_PAUSED)
                        ->select(['orders.order_id', 'orders.from_coordinates', 'orders.to_coordinates', 'orders.status_id'])
                ])
                ->findFirst(['driver_id', 'lat', 'lut', 'current_status_id', 'phone', 'car_id', 'current_franchise_id']);
        }

        if ($this->driver) {
            $this->redis->hmset(ConstRedis::order_calc_data($this->driver->current_order->order_id), ['driver' => igbinary_serialize($this->driver)]);
            $this->redis->expire(ConstRedis::order_calc_data($this->driver->current_order->order_id), 64000);
        }

        return $this->driver;
    }

    /**
     *
     */
    protected function setMinimalDistanceDuration(): void
    {
        $has_redemption_distance = isset($this->tariffs['initial_tariff']['current_tariff']['minimal_distance_value']);
        $has_redemption_duration = isset($this->tariffs['initial_tariff']['current_tariff']['minimal_duration_value']);

        if (!$has_redemption_distance && !$has_redemption_duration) {
            return;
        }

        $has_minimals = $this->redis->hmget(ConstRedis::order_calc_data($this->driver->current_order->order_id), ['min_distance', 'min_duration']);

        if ($has_minimals && ($has_minimals[0] || $has_minimals[1])) {
            return;
        }

        if (!$has_minimals[0] && null !== $this->tariffs['initial_tariff']['current_tariff']['minimal_distance_value']) {
            $this->redis->hmset(ConstRedis::order_calc_data($this->driver->current_order->order_id), [
                'min_distance' => $this->tariffs['initial_tariff']['current_tariff']['minimal_distance_value'] ?? 0
            ]);
        }

        if (!$has_minimals[1] && null !== $this->tariffs['initial_tariff']['current_tariff']['minimal_duration_value']) {
            $this->redis->hmset(ConstRedis::order_calc_data($this->driver->current_order->order_id), [
                'min_duration' => $this->tariffs['initial_tariff']['current_tariff']['minimal_duration_value'] ?? 0
            ]);
        }
    }

    /**
     * @param  OrderProcess  $process
     * @param  Carbon  $started
     * @return \Illuminate\Support\Carbon|null
     */
    protected function passed(OrderProcess $process, Carbon $started): ?\Illuminate\Support\Carbon
    {
        $passed = null;

        if ($process->price_passed) {
            if ($process->price_passed->diffInSeconds($process->cord_updated) >= config('nyt.price_send_interval')) {
                $passed = now();
            }
        } elseif ($started->diffInSeconds($process->cord_updated) >= config('nyt.price_send_interval')) {
            $passed = now();
        } else {
            $passed = null;
        }

        return $passed;
    }

    /**
     * @return Model|null
     */
    protected function getProcessRoad(): ?Model
    {
        return $this->driverService->getProcessRoads($this->driverId, $this->driver->current_order->order_id, true, ['real_road']);
    }

    /**
     * @return mixed
     */
    protected function initialTariffType(): mixed
    {
        return TariffPriceType::getTypeId($this->tariffs['initial_tariff']['tariff_type_id']);
    }

    /**
     * @param $last_cords
     * @param $process
     * @return array
     */
    protected function matrix($last_cords, $process): array
    {
        $distance_line = distance_cords($last_cords[0]['lat'], $last_cords[0]['lut'], $last_cords[1]['lat'], $last_cords[1]['lut'], 'm');

        return $this->distanceDuration($process->cord_updated, $distance_line);
    }

    /**
     * @param $last_cord_time
     * @param $distance_line
     * @return array
     */
    protected function distanceDuration($last_cord_time, $distance_line): array
    {
        $distance_meter = $distance_line;
        $duration_second = $this->penultCordUpdated->diffInSeconds($last_cord_time);

        $meter_second = $duration_second ? round($distance_meter / $duration_second) : 0;
        $km_hr = round($meter_second * 3.6);

        return compact('km_hr', 'distance_meter', 'duration_second');
    }

    protected function annul(): void
    {
        $this->cord = null;
        $this->speed = null;
        $this->driver = null;
        $this->driverId = null;
        $this->penultSpeed = null;
        $this->penultCordUpdated = null;
        $this->tariffs = [];
        $this->passedData = [];
        $this->matrix = ['km_hr' => 0, 'distance_meter' => 0, 'duration_second' => 0];
    }
}
