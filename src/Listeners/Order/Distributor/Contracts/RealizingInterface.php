<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Distributor\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Src\Core\Contracts\Realizing;

/**
 * @property array $tariffs
 * @property array{km_hr: string|int, distance_meter:string|int, duration_second: string|int} $matrix
 * @property object $driver
 * @property array $cord
 */
interface RealizingInterface extends Realizing
{
    /**
     * @param $driver
     * @param $distance
     * @param $duration
     * @param $points
     */
    public function broadcastDriverReceives($driver, $distance, $duration, $points): void;

    /**
     * @param $driver
     * @return bool
     */
    public function setShippedOrders($driver): bool;

    /**
     * @param $rating
     * @param $assessment
     * @param  int  $radius
     * @param  Carbon|null  $sub
     * @param  int  $limit
     * @return mixed
     */
    public function getDrivers($rating, $assessment, int $radius = 1, Carbon $sub = null, int $limit = 3);

    /**
     * @param $rating
     * @param $assessment
     * @param  int  $radius
     * @param  int  $limit
     * @param  array  $reject_driver_ids
     * @param  bool  $free
     * @param  Carbon|null  $interval
     * @return Collection
     */
    public function getPreOrderDrivers(
        $rating,
        $assessment,
        int $radius = 0,
        int $limit = 3,
        array $reject_driver_ids = [],
        bool $free = false,
        Carbon $interval = null
    ): Collection;

    /**
     * @param $drivers
     * @param $s_distance
     * @param $s_duration
     * @return object|null
     */
    public function getDriversFilter($drivers, $s_distance, $s_duration): ?object;

    /**
     * @param  int  $rating
     * @param  float  $assessment
     * @param  int  $radius
     * @param  int  $sub_minute
     * @return mixed
     */
    public function twisting(int $rating = 310, float $assessment = 3.0, int $radius = 1000, int $sub_minute = 60);

    /**
     *
     */
    public function driverNotFound(): void;

    /**
     * @return int
     * @info Get diff minute current time and preorder customer time
     */
    public function getPreorderDiff(): int;
}
