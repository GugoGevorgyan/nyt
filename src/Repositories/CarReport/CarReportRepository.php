<?php

declare(strict_types=1);

namespace Src\Repositories\CarReport;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\CarReport\CarReport;

/**
 * Class CarReportRepository
 * @package Src\Repositories\CarReport
 */
class CarReportRepository extends BaseRepository implements CarReportContract
{
    /**
     * CarReportRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarReport::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('car_reports');
    }
}
