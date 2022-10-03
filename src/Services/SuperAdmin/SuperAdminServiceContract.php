<?php

declare(strict_types=1);


namespace Src\Services\SuperAdmin;


use ServiceEntity\Contract\BaseContract;

/**
 * Interface SuperFranchiserServiceContract
 * @package Src\Services\SuperFranchiser
 */
interface SuperAdminServiceContract extends BaseContract
{
    /**
     * @param $name
     * @param $password
     * @return mixed
     */
    public function checkComparePassword($name, $password);

    /**
     * @param $page
     * @param $per_page
     * @param  string|null  $search
     * @param  string|null  $city
     * @return mixed
     */
    public function airportsDataTable($page, $per_page, string $search = null, string $city = null);

    /**
     * @param  array  $airport
     * @return int|null
     */
    public function createAirport(array $airport): ?int;

    /**
     * @param $airport_id
     * @param  array  $airport
     * @return bool
     */
    public function updateAirport($airport_id, array $airport): bool;

    /**
     * @param  int  $airport_id
     * @return bool
     */
    public function deleteAirport(int $airport_id): bool;

    /**
     * @param $page
     * @param $per_page
     * @param  string|null  $search
     * @param  string|null  $city
     * @return mixed
     */
    public function metrosDataTable($page, $per_page, string $search = null, string $city = null);

    /**
     * @param  array  $metro
     * @return int|null
     */
    public function createMetro(array $metro): ?int;

    /**
     * @param $metro_id
     * @param  array  $metro
     * @return bool
     */
    public function updateMetro($metro_id, array $metro): bool;

    /**
     * @param  int  $metro_id
     * @return bool
     */
    public function deleteMetro(int $metro_id): bool;

    /**
     * @param $page
     * @param $per_page
     * @param  string|null  $search
     * @param  string|null  $city
     * @return mixed
     */
    public function railwayDataTable($page, $per_page, string $search = null, string $city = null);

    /**
     * @param  array  $railway
     * @return int|null
     */
    public function createRailway(array $railway): ?int;

    /**
     * @param $metro_id
     * @param  array  $railway
     * @return bool
     */
    public function updateRailway($metro_id, array $railway): bool;

    /**
     * @param  int  $railway_id
     * @return bool
     */
    public function deleteRailway(int $railway_id): bool;
}
