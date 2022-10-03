<?php

declare(strict_types=1);


namespace Src\Repositories\DriverTypeOptional;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverTypeOptional;

/**
 * Class DriverTypeRepository
 * @package Src\Repositories\DriverTypeOptional
 */
class DriverTypeOptionalRepository extends BaseRepository implements DriverTypeOptionalContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverTypeOptional::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_type_optionals');
    }
}
