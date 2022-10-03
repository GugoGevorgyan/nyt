<?php

declare(strict_types=1);


namespace Src\Repositories\TariffBehind;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\TariffRegionBehind;

/**
 * Class TariffBehindRepository
 * @package Src\Repositories\TariffRegionBehind
 */
class TariffBehindRepository extends BaseRepository implements TariffBehindContract
{
    /**
     * TariffBehindRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(TariffRegionBehind::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('tariff_region_behind');
    }
}
