<?php

declare(strict_types=1);


namespace Src\Repositories\DriverSchedule;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverSchedule;

/**
 * Class DriverScheduleRepository
 * @package Src\Repositories\DriverSchedule
 */
class DriverScheduleRepository extends BaseRepository implements DriverScheduleContract
{
    /**
     * DriverScheduleRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverSchedule::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_schedules');
    }
}
