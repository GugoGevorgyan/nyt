<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Tariff;


use ServiceEntity\Contract\BaseContract;

/**
 * Interface TariffCrudContract
 * @package Src\Services\Tariff
 */
interface TariffCrudContract extends BaseContract
{
    /**
     * @param $tariff_id
     * @param $request
     * @return bool
     */
    public function updateTariff($tariff_id, $request): bool;

    /**
     * @param $tariff_id
     * @return mixed
     */
    public function deleteTariff($tariff_id);

    /**
     * @param $tariff
     * @return mixed
     */
    public function createTariff($tariff): bool;

    /**
     * @param $tariff_id
     * @return null|object
     */
    public function getTariffById($tariff_id): ?object;

    /**
     * @param $request
     * @param $tariff_id
     * @return mixed
     */
    public function copyTariff($request, $tariff_id);

}
