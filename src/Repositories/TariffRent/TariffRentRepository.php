<?php

declare(strict_types=1);

namespace Src\Repositories\TariffRent;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\TariffRent;

/**
 *
 */
class TariffRentRepository extends BaseRepository implements TariffRentContract
{
    /**
     * TariffRegionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(TariffRent::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('tariff_rents');
    }
}
