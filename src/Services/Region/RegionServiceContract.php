<?php

declare(strict_types=1);


namespace Src\Services\Region;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface RegionServiceContract
 * @package Src\Services\Region
 */
interface RegionServiceContract extends BaseContract
{
    /**
     * @return mixed
     */
    public function getAllRegions();

    /**
     * @param  null  $country_id
     * @return array|Collection
     */
    public function getRegionsByCountry($country_id = null): array|Collection;

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getRegionPaginate($request): LengthAwarePaginator;

    /**
     * @param $region_id
     * @return mixed
     */
    public function deleteRegion($region_id);

    /**
     * @param $request
     * @return object|null
     */
    public function createRegion($request): ?object;

    /**
     * @param $request
     * @param $region_id
     * @return mixed
     */
    public function updateRegion($request, $region_id);

    /**
     * @param $request
     * @return mixed
     */
    public function destroyMultiple($request);

    /**
     * @param $geocode
     * @param  null  $company
     * @param  bool  $in_session
     * @return array
     */
    public function detectCountryRegions($geocode, $company = null, bool $in_session = false): array;

    /**
     * @param $region_id
     * @return mixed
     */
    public function getCitiesByRegion($region_id): Collection;

    /**
     * @param $region_ids
     * @return mixed
     */
    public function getCitiesByRegions($region_ids): Collection;

    /**
     * @param $city
     * @return object|null
     */
    public function getTransportsPoints($city): ?object;

    /**
     * @return mixed
     */
    public function getAllCountries();

    /**
     * @param  array  $payload
     * @return LengthAwarePaginator
     */
    public function citiesPager(array $payload): LengthAwarePaginator;

    /**
     * @param  int|null  $region_id
     * @param  int|null  $country_id
     * @return Collection
     */
    public function getAreas(int $region_id = null, int $country_id = null): Collection;

    /**
     * @return array
     */
    public function getTransports(): array;

    /**
     * @param $area_id
     * @return object|null
     */
    public function getRegionsByArea($area_id): ?object;
}
