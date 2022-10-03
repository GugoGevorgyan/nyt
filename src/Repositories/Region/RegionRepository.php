<?php

declare(strict_types=1);


namespace Src\Repositories\Region;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Location\Region;

/**
 * Class RegionRepository
 * @package Src\Repositories\Region
 */
class RegionRepository extends BaseRepository implements RegionContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Region::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('regions');
    }
}
