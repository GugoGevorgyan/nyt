<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Driver;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;
use Src\Http\Requests\Driver\UpdateProfileRequest;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverAddress;

/**
 * Interface DriverCrudContract
 * @package Src\ServicesCrud\Driver
 */
interface DriverCrudContract extends BaseContract
{
    /**
     * @param $driver_id
     * @param $address
     * @param $lat
     * @param $lut
     * @param $target
     * @return DriverAddress|null
     * @throws \JsonException
     */
    public function addFavoriteAddress($driver_id, $address, $lat, $lut, $target): ?object;

    /**
     * @param $driver_id
     * @param $franchise_id
     * @return bool
     */
    public function updateFranchise(int $driver_id, int $franchise_id): bool;

    /**
     * @param $driver_id
     * @return mixed|Driver|null
     */
    public function getDriver($driver_id): ?Driver;

    /**
     * @param  UpdateProfileRequest  $request
     * @return Driver
     */
    public function updateProfile(UpdateProfileRequest $request): ?Driver;

    /**
     * @param $driver_id
     * @return mixed
     */
    public function deleteDriver($driver_id);

    /**
     * @param $driver_id
     * @param $address_id
     * @param $cords
     * @return null|string
     */
    public function selectAddress(int $driver_id, int $address_id, array $cords): ?string;

    /**
     * @param $request
     * @return mixed
     */
    public function franchiseDriversPaginate($request);

    /**
     * @param $driver_id
     * @param $minute
     * @return mixed
     */
    public function blockDriver($driver_id, $minute);

    /**
     * @param $driver_id
     * @return mixed
     */
    public function unBlockDriver($driver_id);

    /**
     * @param $request
     * @return mixed
     */
    public function callCenterDriversPaginate($request): LengthAwarePaginator;

    /**
     * @return array|Collection
     */
    public function callCenterGetDrivers(): array|Collection;

    /**
     * @param $request
     * @return mixed
     */
    public function candidateCreateDriver($request);

    /**
     * @param  int  $driver_id
     * @param  string  $date
     * @return array
     */
    public function getTrajectoryByDate(int $driver_id, string $date): array;
}
