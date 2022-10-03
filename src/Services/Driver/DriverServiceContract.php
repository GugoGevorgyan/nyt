<?php

declare(strict_types=1);


namespace Src\Services\Driver;

use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface DriverServiceContract
 * @package Src\Services\Driver
 */
interface DriverServiceContract extends BaseContract
{
    /**
     * @param  int  $driver_id
     * @param  array  $data
     * @return bool|null
     */
    public function driverOrderReady(int $driver_id, array $data): ?bool;

    /**
     * @param  int  $driver_id
     */
    public function getBelatedCommons(int $driver_id): Collection;

    /**
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  int  $azimuth  degree
     * @param  int  $speed  KM/H
     * @param  int  $driver_id
     * @return void
     */
    public function updateCurrentCoordinates(float $latitude, float $longitude, int $azimuth, int $speed, int $driver_id): void;

    /**
     * @param $hash
     * @param $order_id
     * @param $driver_id
     * @param  bool  $accept
     * @return array|null
     */
    public function driverShippedOrder($hash, $order_id, $driver_id, bool $accept): ?array;

    /**
     * @param $order_id
     * @param $hash
     * @param $driver_id
     * @param  int|null  $selected_route_id
     * @param  bool  $accept
     * @return bool|string|array
     */
    public function driverOnWay($order_id, $hash, $driver_id, int $selected_route_id = null, bool $accept = true): bool|string|array;

    /**
     * @param $order_id
     * @param $hash
     * @param $cord
     * @return array|null
     */
    public function driverInPlace($order_id, $hash, $cord): ?array;

    /**
     * @param $order_id
     * @param $selected_route_id
     * @return mixed
     */
    public function responseInStartSelectedRoute($order_id, $selected_route_id);

    /**
     * @param $order_id
     * @param $hash
     * @param $driver_id
     * @param  null  $route_or_lat
     * @param  null  $lut
     * @return Collection|null
     */
    public function responseInStartOrder($order_id, $hash, $driver_id, $route_or_lat = null, $lut = null): ?Collection;

    /**
     * @param $order_id
     * @param $hash
     * @param $cords
     * @return null|Collection
     */
    public function orderEnd($order_id, $hash, $cords): ?Collection;

    /**
     * @param $driver_id
     * @return array
     */
    public function getDriverUpdatedDriverData($driver_id): array;

    /**
     * @param $hash_name
     * @param $hash
     * @param $driver_id
     * @return mixed
     */
    public function deleteOnWayRoads($hash_name, $hash, $driver_id);

    /**
     * @param $driver_id
     * @param $order_id
     * @param  bool  $selected
     * @param  array|string[]  $values
     * @return object|null
     */
    public function getProcessRoads($driver_id, $order_id, bool $selected = false, array $values = ['*']): ?object;

    /**
     * @return mixed
     */
    public function getTypes(): Collection;

    /**
     * @return mixed
     */
    public function getTypesWithOptions(): Collection;

    /**
     * @param $request
     * @return bool
     */
    public function updateFranchiseOptionals($request): ?object;

    /**
     * @param  int  $driver_id
     * @return Collection
     */
    public function getDaysOrders(int $driver_id): Collection;

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  string  $hash
     * @param  bool  $accept
     * @return bool|array
     */
    public function acceptCommonOrder(int $driver_id, int $order_id, string $hash, bool $accept): bool|array;

    /**
     * @param $driver_id
     * @param  array|null  $options
     * @return array
     */
    public function getSelectedOptionsOrderCount($driver_id, array $options = null): array;

    /**
     * @param $driver_id
     * @param $classes
     * @return array
     */
    public function getSelectedClassesOrderCount($driver_id, $classes): array;

    /**
     * @param $driver_id
     * @return array
     */
    public function getProfileInitialData($driver_id): array;

    /**
     * @param  int  $driver_id
     * @param  bool  $status_online
     * @return bool
     */
    public function changeOnlineStatus(int $driver_id, bool $status_online): bool;

    /**
     * @param $driver_id
     * @return null|array
     */
    public function getRealState($driver_id): ?array;

    /**
     * @param $driver_id
     * @param $image
     * @return void
     */
    public function profileImageUpload($driver_id, $image): void;

    /**
     * @param  int  $driver_id
     * @param  int  $classes
     */
    public function toggleCarClass(int $driver_id, int $classes): void;

    /**
     * @param  int  $driver_id
     * @param  int  $options
     * @return mixed
     */
    public function toggleCarOption(int $driver_id, int $options): void;

    /**
     * @param $driver_id
     * @return bool
     */
    public function driverHasAcceptOrder($driver_id): bool;

    /**
     * @param $driver_id
     * @return bool
     */
    public function driverIsRejectOrder($driver_id): bool;

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @param  int  $option
     * @param  string|null  $text
     * @return array
     */
    public function driverRejectOrder(int $order_id, int $driver_id, int $option, string $text = null): array;

    /**
     * @param  string  $search
     * @return Collection
     */
    public function searchDrivers(string $search): Collection;

    /**
     * @param $driver_id
     * @return mixed
     */
    public function getDebt($driver_id): mixed;

    /**
     * @param  int  $driver_id
     * @return array
     */
    public function getCommonOrders(int $driver_id): array;

    /**
     * @param  int  $driver_id
     * @return Collection
     */
    public function getCommonArmorsOrders(int $driver_id): array;

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @return bool
     */
    public function cancelCommonOrder(int $driver_id, int $order_id): bool;

    /**
     * @param $hash
     * @param $driver_id
     * @param $start_cord
     * @param  int|null  $selected_route_id
     */
    public function startCordSave($hash, $driver_id, $start_cord, ?int $selected_route_id = null): void;

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  bool  $accept
     */
    public function questionPreorderAccept(int $driver_id, int $order_id, $started, bool $accept): void;

    /**
     * @param $driver_id
     * @param $driver_info_id
     * @param $data
     * @return bool
     */
    public function updateDriverInfoFields($driver_id, $driver_info_id, $data): bool;

    /**
     * @param $driver_id
     * @param $new_password
     * @return bool
     */
    public function updateDriverPassword($driver_id, $new_password): bool;


    /**
     * @param $driver_id
     * @param $car_id
     * @return bool
     */
    public function removeDriverOnCar($driver_id, $car_id): bool;
}
