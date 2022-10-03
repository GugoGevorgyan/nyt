<?php

declare(strict_types=1);


namespace Src\Repositories\DriverInfo;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverInfo;

/**
 * Class DriverLicenseRepository
 * @package Src\Repositories\DriverInfo
 */
class DriverInfoRepository extends BaseRepository implements DriverInfoContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverInfo::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('drivers_info');
    }
}
