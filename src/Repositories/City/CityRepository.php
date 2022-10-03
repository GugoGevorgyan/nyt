<?php

declare(strict_types=1);


namespace Src\Repositories\City;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Location\City;

/**
 * Class City
 * @package Src\Repositories\City
 */
class CityRepository extends BaseRepository implements CityContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(City::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('cities');
    }
}
