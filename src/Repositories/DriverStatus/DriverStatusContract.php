<?php

declare(strict_types=1);


namespace Src\Repositories\DriverStatus;


use Illuminate\Support\Collection;
use Repository\Contracts\BaseRepositoryContract;

/**
 * Interface DriverStatusContract
 * @package Src\Repositories\DriverStatus
 */
interface DriverStatusContract extends BaseRepositoryContract
{

    /**
     * @return array|Collection
     */
    public function getDriverStatuses(): array|Collection;
}
