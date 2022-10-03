<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Calc\Contracts;

use Carbon\Carbon;
use Src\Core\Contracts\Realizing;
use Src\Models\Order\OrderProcess;

/**
 * @property array $tariffs
 * @property array{km_hr: string|int, distance_meter:string|int, duration_second: string|int} $matrix
 * @property object $driver
 * @property array $cord
 */
interface CounterInterface extends Realizing
{
    /**
     * @return string|int
     */
    public function currentTariffType(): string|int;

    /**
     * @return array
     */
    public function getMinimalDistDur(): array;

    /**
     * @param $process
     * @return float|int|string
     */
    public function getCoreCalculate($process): float|int|string;

    /**
     * @param $process
     * @return int|string|float
     */
    public function getCoreTotal($process): float|int|string;

    /**
     * @param  OrderProcess  $process
     * @param  array  $matrix
     * @return bool
     */
    public function isDistDurTraveled(OrderProcess $process, array $matrix): bool;

    /**
     * @param  OrderProcess  $process
     * @param  array{price: string|int|float, calculate_price: string|int|float, increment_price:string|int|float, duration_second: string|int,distance_meter:string|int}  $result
     * @param  Carbon  $order_started
     * @return void
     */
    public function savePriceData(OrderProcess $process, array $result, Carbon $order_started): void;

    /**
     * @param $travel_time
     * @return mixed
     */
    public function rentCriterion($travel_time);
}
