<?php

declare(strict_types=1);


namespace Src\Repositories\Route;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Views\Route;

/**
 * Class RouteRepository
 * @package Src\Repositories\Route
 */
class RouteRepository extends BaseRepository implements RouteContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Route::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('routes');
    }
}
