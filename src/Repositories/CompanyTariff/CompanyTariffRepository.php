<?php

declare(strict_types=1);


namespace Src\Repositories\CompanyTariff;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\CompanyTariff;

/**
 * Class CompanyTariffRepository
 * @package Src\Repositories\CompanyTariff
 */
class CompanyTariffRepository extends BaseRepository implements CompanyTariffContract
{
    /**
     * CompanyRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CompanyTariff::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('company_tariff');
    }
}
