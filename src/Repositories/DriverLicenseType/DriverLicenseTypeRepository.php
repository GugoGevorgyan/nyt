<?php

declare(strict_types=1);


namespace Src\Repositories\DriverLicenseType;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverLicenseType;

/**
 * Class DriverLicenseTypeRepository
 * @package Src\Repositories\DriverLicenseType
 */
class DriverLicenseTypeRepository extends BaseRepository implements DriverLicenseTypeContract
{
    /**
     * DestinationRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverLicenseType::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_license_types');
    }
}
