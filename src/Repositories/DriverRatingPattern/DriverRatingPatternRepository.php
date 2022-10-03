<?php

declare(strict_types=1);


namespace Src\Repositories\DriverRatingPattern;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\RatingSystem\DriverRatingPattern;

/**
 * Class DriverRatingSystemPatternRepository
 * @package Src\Repositories\DriverRatingPattern
 */
class DriverRatingPatternRepository extends BaseRepository implements DriverRatingPatternContract
{

    /**
     * CarOptionRepository constructor.
     *
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverRatingPattern::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('driver_rating_patterns');
    }
}
