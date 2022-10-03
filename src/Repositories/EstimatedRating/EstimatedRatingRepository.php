<?php

declare(strict_types=1);


namespace Src\Repositories\EstimatedRating;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\RatingSystem\EstimatedRating;

/**
 * Class EstimatedRatingRepository
 * @package Src\Repositories\EstimatedRating
 */
class EstimatedRatingRepository extends BaseRepository implements EstimatedRatingContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(EstimatedRating::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('estimated_ratings');
    }
}
