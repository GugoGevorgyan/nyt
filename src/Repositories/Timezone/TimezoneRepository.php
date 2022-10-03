<?php

declare(strict_types=1);

namespace Src\Repositories\Timezone;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Location\Timezone;

/**
 *
 */
class TimezoneRepository extends BaseRepository implements TimezoneContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Timezone::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('timezones');
    }
}
