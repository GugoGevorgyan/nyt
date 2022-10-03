<?php

declare(strict_types=1);


namespace Src\Repositories\Country;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Location\Country;

/**
 * Class CountryRepository
 * @package Src\Repositories\Country
 */
class CountryRepository extends BaseRepository implements CountryContract
{
    /**
     * CountryRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Country::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('countries');
    }
}
