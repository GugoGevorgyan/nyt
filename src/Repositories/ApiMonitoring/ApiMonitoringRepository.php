<?php

declare(strict_types=1);


namespace Src\Repositories\ApiMonitoring;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Monitor\ApiMonitoring;

/**
 * Class ApiMonitoringRepository
 * @package Src\Repositories\ApiMonitoringListen
 */
class ApiMonitoringRepository extends BaseRepository implements ApiMonitoringContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ApiMonitoring::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('api_monitoring');
    }
}
