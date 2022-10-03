<?php

declare(strict_types=1);


namespace Src\Repositories\CarCrash;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Car\CarCrash;

/**
 * Class CarCrashRepository
 * @package Src\Repositories\CarCrash
 */
class CarCrashRepository extends BaseRepository implements CarCrashContract
{
    /**
     * CarCrashRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarCrash::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('car_crashes');
    }
}
