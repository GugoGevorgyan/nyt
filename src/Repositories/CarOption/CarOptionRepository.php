<?php

declare(strict_types=1);


namespace Src\Repositories\CarOption;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Car\CarOption;

/**
 * Class CarOptionRepository
 * @package Src\Repositories\CarOption
 */
class CarOptionRepository extends BaseRepository implements CarOptionContract
{

    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarOption::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('car_options');
    }
}
