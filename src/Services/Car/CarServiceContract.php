<?php

declare(strict_types=1);


namespace Src\Services\Car;

use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;
use Src\Repositories\Tariff\TariffContract;

/**
 * Interface CarServiceContract
 * @package Src\Services\Car
 */
interface CarServiceContract extends BaseContract
{
    /**
     * @param  int  $company_id
     * @param  int|null  $client_id
     * @return Collection
     */
    public function getCompanyCarClasses(int $company_id, int $client_id = null): Collection;

    /**
     * @return Collection
     */
    public function getCarClasses(): Collection;

    /**
     * @param  int  $franchise_id
     * @param  int|null  $client_id
     * @return Collection
     */
    public function getFranchiseCarClasses(int $franchise_id, int $client_id = null): Collection;

    /**
     * @return Collection
     */
    public function getCarStatuses(): Collection;

    /**
     * @return Collection
     */
    public function getAllOptions(): Collection;

    /**
     * @param $worker_data
     * @return mixed
     */
    public function mechanicRefreshToken($worker_data): ?array;

    /**
     * @param $request
     * @return bool|null
     */
    public function logoutWorker($request): ?bool;

    /**
     * @param $request
     * @return bool
     */
    public function updateInfo($request): bool;

    /**
     * @param  array  $cords
     * @param  null  $company_id
     * @param  array  $options
     * @param  bool  $is_rent
     * @return Collection|null
     */
    public function getCarClassesWithMinPrice(array $cords, $company_id = null, array $options = [], bool $is_rent = false): ?Collection;

    /**
     * @param  int  $company_id
     * @param  int  $client_id
     * @return Collection
     */
    public function carClassByCompany(int $company_id, int $client_id): Collection;

    /***
     * @param  array  $options
     * @return float|null
     */
    public function calculateOrderOptions(array $options): ?float;

    /**
     * @return Collection|null
     */
    public function getCarOptions(array $values = []): ?Collection;

    /**
     * @param $option_id
     * @param $class_id
     * @param  null  $tariff_id
     * @return string|float|null
     */
    public function getOptionPrice($option_id, $class_id, $tariff_id = null): float|string|null;

    /**
     * @param $class_id
     * @param  null  $tariff_id
     * @return array
     */
    public function getOptions($class_id, $tariff_id = null): array;
}
