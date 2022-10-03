<?php

declare(strict_types=1);


namespace Src\Repositories\ClassOption;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Car\ClassOptionTariff;

/**
 * Class ClassClassOptionRepository
 * @package Src\Repositories\OptionTariff
 */
class ClassOptionRepository extends BaseRepository implements ClassOptionContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ClassOptionTariff::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('class_option_tariff');
    }
}
