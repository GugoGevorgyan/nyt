<?php

declare(strict_types=1);


namespace Src\Repositories\DriverContract;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverContract;

/**
 * Class DriverContractRepository
 * @package Src\Repositories\DriverContract
 */
class DriverContractRepository extends BaseRepository implements DriverContractContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverContract::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_contracts');
    }
}
