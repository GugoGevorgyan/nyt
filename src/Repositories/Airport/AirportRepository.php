<?php

declare(strict_types=1);


namespace Src\Repositories\Airport;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\TransportStations\Airport;

/**
 * Class AirportRepository
 * @package Src\Repositories\Airport
 */
class AirportRepository extends BaseRepository implements AirportContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Airport::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('airports');
    }
}
