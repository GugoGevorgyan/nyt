<?php

declare(strict_types=1);


namespace Src\Repositories\DriverStatus;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverStatus;

/**
 * Class DriverStatusRepository
 * @package Src\Repositories\DriverStatus
 */
class DriverStatusRepository extends BaseRepository implements DriverStatusContract
{

    /**
     * DriverScheduleRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverStatus::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('driver_statuses');
    }


    /**
     * @return array|Collection
     */
    public function getDriverStatuses(): array|Collection
    {
        return $this->findAll(['driver_status_id', 'name', 'status', 'text', 'color']) ?: [];
    }
}
