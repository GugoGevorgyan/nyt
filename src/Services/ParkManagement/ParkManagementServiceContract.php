<?php

declare(strict_types=1);

namespace Src\Services\ParkManagement;

use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface ParkManagementServiceContract
 * @package Src\Services\ParkManagement
 */
interface ParkManagementServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return mixed
     */
    public function updateDriverCar($request);

    /**
     * @param $request
     * @return mixed
     */
    public function carsPaginate($request);

    /**
     * @param  array  $values
     * @return mixed
     */
    public function getParks(array $values = ['*']);

    /**
     * @param $search
     * @return Collection
     */
    public function getFreeDrivers($search): Collection;

    /**
     * @param $request
     * @return mixed
     */
    public function updateStatus($request);
}
