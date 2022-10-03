<?php

declare(strict_types=1);


namespace Src\Repositories\DriverRatingLevel;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverRatingLevel;

/**
 * Class DriverRatingLevelRepository
 * @package Src\Repositories\DriverRatingLevel
 */
class DriverRatingLevelRepository extends BaseRepository implements DriverRatingLevelContract
{
    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverRatingLevel::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('driver_rating_levels');
    }
}
