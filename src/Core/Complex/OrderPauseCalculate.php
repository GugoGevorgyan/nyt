<?php

declare(strict_types=1);


namespace Src\Core\Complex;


use Carbon\Carbon;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Src\Core\Enums\ConstRedis;
use Src\Core\Traits\Complex;
use Src\Models\Client\Client;
use Src\Models\Driver\Driver;
use Src\Models\Order\Order;
use Src\Models\Tariff\Tariff;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Services\Tariff\TariffServiceContract;

/**
 * Class OrderPauseCalculate
 * @return array
 * @package Src\Core\Complex
 * @method static complex(Driver $driver, Client $client, Order $order, Tariff $tariff, Carbon $start);
 * @property TariffContract $tariffContract
 * @property TariffServiceContract $tariffService
 */
final class OrderPauseCalculate
{
    use Complex;

    /**
     * @var Carbon
     */
    protected Carbon $now;
    /**
     * @var Connection|Redis
     */
    protected Redis|Connection $redis;

    /**
     * OrderPauseCalculate constructor.
     * @param  Driver  $driver
     * @param  Client  $client
     * @param  Order  $order
     * @param  Tariff  $tariff
     * @param  Carbon  $start
     */
    public function __construct(protected Driver $driver, protected Client $client, protected Order $order, protected Tariff $tariff, protected Carbon $start)
    {
        $this->redis = $this->redis();
    }

    /**
     * @return array
     */
    public function handle(OrderProcessContract $orderProcessContract): array
    {
        $this->now = now();

        $time_passed = $this->redis->hmget(ConstRedis::order_pause_time($this->order->order_id), ['time', 'passed']);
        [$time, $passed] = [(int)$time_passed[0], $time_passed[1]];
        $diff_passed = $passed ? $this->now->diffInSeconds(Carbon::parse($passed)) : 0;
        $paid_seconds = ($this->tariff->current_tariff->free_wait_stop_minutes + 1) * 60;

        if ($paid_seconds > $time) {
            return $this->calculate($time);
        }

        if ($paid_seconds < $time && $passed && $diff_passed < 60) {
            return $this->calculate($time);
        }

        if (!$diff_passed || ($paid_seconds <= $time && $diff_passed >= 60)) {
            return $this->calculateIncrement($time);
        }

        return $this->get();
    }

    /**
     * @param $time
     * @return array
     */
    protected function calculate($time): array
    {
        $travel_time = $this->driver->process->travel_time + $this->start->diffInSeconds($this->now);
        $this->driver->process->update(['pause_time' => $time, 'travel_time' => $travel_time]);

        return $this->get($this->driver->process->total_price, $this->driver->process->distance_traveled, $time, $travel_time);
    }

    /**
     * @param  float  $price
     * @param  float  $distance
     * @param  int  $pause
     * @param  int  $travel_time
     * @return array
     */
    protected function get($price = 0.0, $distance = 0.0, $pause = 0, $travel_time = 0): array
    {
        return ['price' => $price, 'distance' => round_d($distance), 'pause' => round($pause / 60), 'travel_time' => round($travel_time / 60)];
    }

    /**
     * @param $time
     * @return array
     */
    protected function calculateIncrement($time): array
    {
        $travel_time = $this->driver->process->travel_time + $this->start->diffInSeconds($this->now);
        $total_price = $this->driver->process->total_price + $this->tariff->current_tariff->paid_wait_stop_minute;
        $pause_price = $this->driver->process->pause_price + $this->tariff->current_tariff->paid_wait_stop_minute;

        $this->driver->process
            ->update([
                'pause_time' => $time,
                'travel_time' => $travel_time,
                'pause_price' => $pause_price,
                'total_price' => $total_price,
            ]);

        $this->redis->hmset(ConstRedis::order_pause_time($this->order->order_id), ['passed' => $this->now->format('Y-m-d H:i:s')]);

        return $this->get($total_price, $this->driver->process->distance_traveled, $time, $travel_time);
    }
}
