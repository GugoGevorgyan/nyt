<?php

declare(strict_types=1);


namespace Src\Repositories\Preorder;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\PreOrder;

/**
 * Class OrderScheduleRepoisitory
 * @package Src\Repositories\PreOrder
 */
class PreorderRepository extends BaseRepository implements PreorderContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(PreOrder::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('preorders');
    }
}
