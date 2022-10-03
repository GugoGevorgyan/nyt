<?php

declare(strict_types=1);

namespace Src\Services\Tariff;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface TariffServiceContract
 * @package Src\Services\Tariff
 */
interface TariffServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function adminPaginate($request): LengthAwarePaginator;

    /**
     * @param  array  $from
     * @param  array  $to
     * @param  array  $options
     * @return array|null
     */
    public function getTariff(array $from, array $to = [], array $options = [], bool $is_rent = false): ?array;

    /**
     * @param  array  $tariffs
     * @param  array  $from
     * @param  array  $to
     * @param  array  $options
     * @return mixed
     */
    public function getPriceByTariff(array $tariffs, array $from, array $to, array $options, bool $is_rent = false);

    /**
     * @param $class_id
     * @return array
     */
    public function getRentTimesByData($class_id): array;

    /**
     * @param $in_order_hash
     * @param $driver_id
     * @param $from_cord
     * @param $to_cord
     * @return array
     */
    public function driverOnWayPriceCalculate($in_order_hash, $driver_id, $from_cord, $to_cord): array;

    /**
     * @param $city
     * @return mixed
     */
    public function getRegionsCitiesTariffs($city);

    /**
     * @return mixed
     */
    public function getTariffsForCompanies(): array;

    /**
     * @return Collection
     */
    public function getTariffTypes(): Collection;

    public function getAlternativeTariffs($car_class_id, $country_id);
}
