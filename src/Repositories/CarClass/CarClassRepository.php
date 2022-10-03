<?php

declare(strict_types=1);


namespace Src\Repositories\CarClass;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Car\CarClass;

/**
 * Class CarClassRepository
 * @package Src\Repositories\CarClass
 */
class CarClassRepository extends BaseRepository implements CarClassContract
{
    /**
     * CarClassRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarClass::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('cars_class');
    }
}
