<?php

declare(strict_types=1);


namespace Src\Repositories\DriverInfoLicenseType;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverInfoLicenseType;

/**
 * Class DriverInfoLicenseTypeRepository
 * @package Src\Repositories\DriverInfoLicenseType
 */
class DriverInfoLicenseTypeRepository extends BaseRepository implements DriverInfoLicenseTypeContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverInfoLicenseType::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('drivers_info_license_types');
    }
}
