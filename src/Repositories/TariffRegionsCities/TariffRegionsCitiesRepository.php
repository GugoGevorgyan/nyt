<?php

declare(strict_types=1);


namespace Src\Repositories\TariffRegionsCities;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\TariffRegionCity;

/**
 * Class TariffRegionsCitiesRepository
 * @package Src\Repositories\TariffRegionsCities
 */
class TariffRegionsCitiesRepository extends BaseRepository implements TariffRegionsCitiesContract
{
    /**
     * TariffRegionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(TariffRegionCity::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('tariff_regions_cities');
    }
}
