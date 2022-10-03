<?php

declare(strict_types=1);

namespace Src\Repositories\DriverCoordinate;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverCoordinate;

/**
 *
 */
class DriverCoordinateRepository extends BaseRepository implements DriverCoordinateContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverCoordinate::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_coordinates');
    }
}
