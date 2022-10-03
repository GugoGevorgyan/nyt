<?php

declare(strict_types=1);


namespace Src\Repositories\DriverLock;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverLock;

/**
 * Class DriverLockRepository
 * @package Src\Repositories\DriverLock
 */
class DriverLockRepository extends BaseRepository implements DriverLockContract
{
    /**
     * DestinationRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverLock::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('drivers_lock');
    }
}
