<?php

declare(strict_types=1);


namespace Src\Services\Park;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface ParkServiceContract
 * @package Src\Services\Park
 */
interface ParkServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function parkPaginate($request): LengthAwarePaginator;

    /**
     * @param $request
     * @return object|null
     */
    public function createPark($request): ?object;

    /**
     * @param  $request
     * @param  $park
     * @return mixed
     */
    public function updatePark($request, $park);

    /**
     * @param  $park_id
     * @return mixed
     */
    public function deletePark($park_id);

    /**
     * @return mixed
     */
    public function getFranchiseParks(): Collection;

    /**
     * @param $fields
     * @return mixed
     */
    public function getParksFields($fields): Collection;

    /**
     * @param $park_id
     * @return mixed
     */
    public function getParkDrivers($park_id);
}
