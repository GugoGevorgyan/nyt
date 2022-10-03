<?php
declare(strict_types=1);


namespace Src\Services\DriverSchedule;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface DriverScheduleServiceContract
 * @package Src\Services\DriverSchedule
 */
interface DriverScheduleServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function driversPaginate($request): LengthAwarePaginator;

    /**
     * @param $request
     * @return bool
     */
    public function updateDriverSchedule($request): bool;

    /**
     * @param $contract
     * @param $date
     * @return bool
     */
    public function createDriverSchedule($contract, $date): bool;
}
