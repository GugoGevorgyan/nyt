<?php

declare(strict_types=1);

namespace Src\Services\TrafficSafety;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface TrafficSafetyServiceContract
 * @package Src\Services\TrafficSafety
 */
interface TrafficSafetyServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function carsPaginate($request): LengthAwarePaginator;

    /**
     * @param $car_id
     * @return mixed
     */
    public function getFranchiseCar($car_id);

    /**
     * @return mixed
     */
    public function getParks();

    /**
     * @param  int  $car_id
     * @param  array  $request
     * @return mixed
     */
    public function updateCar(int $car_id, array $request);

    /**
     * @param $request
     * @return mixed
     */
    public function updatePark($request);

    /**
     * @param $request
     * @return mixed
     */
    public function updateStatus($request);

    /**
     * @param $car
     * @param $page
     * @param $per_page
     * @return LengthAwarePaginator
     */
    public function getCrashes($car, $page, $per_page): LengthAwarePaginator;

    /**
     * @return mixed
     */
    public function getCarClasses();

    /**
     * @return mixed
     */
    public function getStatuses();

    /**
     * @param  array  $car_data
     * @return object|null
     */
    public function createCar(array $car_data): ?object;

    /**
     * @param  array  $data
     * @return mixed
     */
    public function createCrash(array $data);

    /**
     * @param $crash
     * @return object|bool
     */
    public function deleteCrash($crash): object|bool;

    /**
     * @param $request
     * @return bool
     */
    public function updateInspection($request): bool;

    /**
     * @param $request
     * @return bool
     */
    public function updateInsurance($request): bool;

    /**
     * @param  int  $car_id
     * @param  string  $type
     * @return string
     */
    public function downloadStsPts(int $car_id, string $type): string;
}
