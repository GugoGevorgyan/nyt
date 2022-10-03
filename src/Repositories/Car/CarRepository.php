<?php

declare(strict_types=1);


namespace Src\Repositories\Car;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Car\Car;

/**
 * Class CarRepository
 * @package Src\Repositories\Car
 */
class CarRepository extends BaseRepository implements CarContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Car::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('cars');
    }
}
