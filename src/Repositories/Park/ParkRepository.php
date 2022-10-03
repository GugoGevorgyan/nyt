<?php

declare(strict_types=1);


namespace Src\Repositories\Park;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Park;

/**
 * Class Park
 * @package Src\Repositories\Park
 */
class ParkRepository extends BaseRepository implements ParkContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Park::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('parks');
    }
}
