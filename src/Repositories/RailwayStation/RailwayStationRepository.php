<?php

declare(strict_types=1);


namespace Src\Repositories\RailwayStation;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\TransportStations\RailwayStation;

/**
 * Class RailwayStationRepository
 * @package Src\Repositories\RailwayStation
 */
class RailwayStationRepository extends BaseRepository implements RailwayStationContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(RailwayStation::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('railway_stations');
    }
}
