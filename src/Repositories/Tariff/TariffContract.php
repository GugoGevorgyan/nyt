<?php

declare(strict_types=1);


namespace Src\Repositories\Tariff;


use Illuminate\Support\Collection;
use Repository\Contracts\BaseRepositoryContract;
use Src\Models\Tariff\Tariff;

/**
 * Interface TariffContract
 * @package Src\Repositories\Tariff
 */
interface TariffContract extends BaseRepositoryContract
{
    /**
     * @param $order_id
     * @return Collection {}
     */
    public function getTariffByOrder($order_id): Collection;

    /**
     * @param $tariff_id
     * @param  array|string[]  $tariff_values
     * @return Tariff|null
     */
    public function getTariffWithArea($tariff_id, array $tariff_values = ['*']): ?Tariff;
}
