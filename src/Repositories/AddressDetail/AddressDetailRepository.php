<?php

declare(strict_types=1);


namespace Src\Repositories\AddressDetail;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Monitor\AddressDetail;

/**
 * Class AddressDetailRepository
 * @package Src\Repositories\AddressDetail
 */
class AddressDetailRepository extends BaseRepository implements AddressDetailContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(AddressDetail::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('addresses_details');
    }
}
