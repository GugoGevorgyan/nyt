<?php

declare(strict_types=1);


namespace Src\Repositories\Driver;


use Illuminate\Support\Collection;
use Repository\Contracts\BaseRepositoryContract;

/**
 * Interface DriverContract
 * @package Src\Repositories\DriverRepository
 * @method distance($lat, $lut)
 * @method distanceCord(float $latitude, float $longitude, int $int)
 */
interface DriverContract extends BaseRepositoryContract
{
    /**
     * @param $franchise_id
     * @param  array|string[]  $values
     * @return Collection
     */
    public function getFranchiseDrivers($franchise_id, array $values = ['*']): Collection;

    /**
     * @param $driver_id
     * @return null|array{lat: float|string, lut: float|string}
     */
    public function getCordArray($driver_id): ?array;
}
