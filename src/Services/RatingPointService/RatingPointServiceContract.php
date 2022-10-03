<?php

declare(strict_types=1);


namespace Src\Services\RatingPointService;

use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;
use Src\Models\RatingSystem\EstimatedRating;

/**
 * Interface RatingPointServiceContract
 *
 * @package Src\Services\RatingPointService
 */
interface RatingPointServiceContract extends BaseContract
{
    /**
     * @param $driver_id
     * @param $order_id
     * @param  null  $distance
     * @param  null  $duration
     * @param  bool  $multi
     * @return array
     * @example string "added, remove"
     */
    public function calculateRating($driver_id, $order_id, $distance = null, $duration = null, bool $multi = true): array;

    /**
     * @param $driver_id
     * @param $order_id
     * @param $patterns
     * @return Collection
     */
    public function setDriverRating($driver_id, $order_id, $patterns): Collection;

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @return mixed
     */
    public function getRating(int $order_id, int $driver_id): ?EstimatedRating;
}
