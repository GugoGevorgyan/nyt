<?php

declare(strict_types=1);


namespace Src\Repositories\DriverType;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverType;

/**
 * Class DriverTypeRepository
 * @package Src\Repositories\DriverType
 */
class DriverTypeRepository extends BaseRepository implements DriverTypeContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverType::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('driver_types');
    }

    /**
     * @return Collection
     * @throws JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getDriverTypes(): Collection
    {
        return $this->findAll() ?: [];
    }
}
