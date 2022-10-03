<?php

declare(strict_types=1);


namespace Src\Repositories\Waybill;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Terminal\Waybill;

/**
 * Class WaybillRepository
 * @package Src\Repositories\Waybill
 */
class WaybillRepository extends BaseRepository implements WaybillContract
{
    /**
     * WaybillRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Waybill::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('waybills');
    }
}
