<?php

declare(strict_types=1);


namespace Src\Repositories\DriverGraphic;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverGraphic;

/**
 * Class DriverGraphicRepository
 * @package Src\Repositories\DriverGraphic
 */
class DriverGraphicRepository extends BaseRepository implements DriverGraphicContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverGraphic::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_graphics');
    }

    /**
     * @inheritDoc
     */
    public function getGraphics()
    {
        return $this->model()::all();
    }
}
