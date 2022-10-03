<?php

declare(strict_types=1);


namespace Src\Repositories\Driver;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\Driver;

/**
 * Class DriverRepository
 * @package Src\Repositories\DriverRepository
 */
class DriverRepository extends BaseRepository implements DriverContract
{
    /**
     * DriverRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Driver::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('drivers');
    }

    /**
     * @inheritdoc
     */
    public function getFranchiseDrivers($franchise_id, array $values = ['*']): Collection
    {
        return $this
            ->where('current_franchise_id', '=', $franchise_id)
            ->with(['driver_info' => fn($query) => $query->select(['*'])])
            ->findAll($values);
    }

    /**
     * @param $driver_id
     * @return array|null
     * @throws JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCordArray($driver_id): ?array
    {
        $driver = $this->find($driver_id, ['driver_id', 'lat', 'lut']);

        if (!$driver) {
            return null;
        }

        return ['lat' => $driver->lat, 'lut' => $driver->lut];
    }
}
