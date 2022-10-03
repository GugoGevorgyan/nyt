<?php

declare(strict_types=1);

namespace Src\Repositories\TariffType;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\TariffPriceType;

/**
 * Class TariffTypeRepository
 * @package Src\Repositories\TariffPriceType
 */
class TariffTypeRepository extends BaseRepository implements TariffTypeContract
{
    /**
     * TariffTypeRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(TariffPriceType::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('tariff_types');
    }
}
