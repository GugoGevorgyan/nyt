<?php

declare(strict_types=1);


namespace Src\Repositories\CarCrashImage;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Car\CarCrashImage;

/**
 * Class CarCrashImageRepository
 * @package Src\Repositories\CarCrashImage
 */
class CarCrashImageRepository extends BaseRepository implements CarCrashImageContract
{
    /**
     * CarCrashImageRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarCrashImage::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('car_crash_images');
    }
}
