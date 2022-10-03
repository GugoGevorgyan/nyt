<?php

declare(strict_types=1);


namespace Src\Repositories\Area;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Location\Area;

/**
 * Class AreaRepository
 * @package Src\Repositories\Area
 */
class AreaRepository extends BaseRepository implements AreaContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Area::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('areas');
    }
}
