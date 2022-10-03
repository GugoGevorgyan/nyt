<?php

namespace Src\Repositories\TariffRentAlt;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\TariffRentAlt;

class TariffRentAltRepository extends BaseRepository implements TariffRentAltContract
{
    /**
     * TariffRegionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(TariffRentAlt::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('tariff_rents');
    }
}
