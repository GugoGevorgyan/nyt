<?php

declare(strict_types=1);


namespace Src\Repositories\DriverTypeOption;


use Illuminate\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverTypeOption;

/**
 * Class DriverTypeOptionRepository
 * @package Src\Repositories\DriverTypeOption
 */
class DriverTypeOptionRepository extends BaseRepository implements DriverTypeOptionContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverTypeOption::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_type_option');
    }
}
