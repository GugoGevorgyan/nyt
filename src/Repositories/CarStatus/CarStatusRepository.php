<?php

declare(strict_types=1);


namespace Src\Repositories\CarStatus;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Car\CarStatus;

/**
 * Class CarStatusRepository
 * @package Src\Repositories\CarStatus
 */
class CarStatusRepository extends BaseRepository implements CarStatusContract
{

    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarStatus::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('car_statuses');
    }
}
