<?php

declare(strict_types=1);


namespace Src\Repositories\TariffDestination;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\TariffDestination;

/**
 * Class TariffDestinationRepository
 * @package Src\Repositories\TariffDestination
 */
class TariffDestinationRepository extends BaseRepository implements TariffDestinationContract
{
    /**
     * TariffRegionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(TariffDestination::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('tariff_destinations');
    }
}
