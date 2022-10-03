<?php

declare(strict_types=1);


namespace Src\Repositories\DriverCandidate;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverCandidate;

/**
 * Class DriverCandidateRepository
 * @package Src\Repositories\DriverCandidate
 */
class DriverCandidateRepository extends BaseRepository implements DriverCandidateContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverCandidate::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_candidates');
    }
}
