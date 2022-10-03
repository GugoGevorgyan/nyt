<?php

declare(strict_types=1);


namespace Src\Repositories\PaymentType;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\PaymentType;

/**
 * Class PaymentTypeRepository
 * @package Src\Repositories\PaymentType
 */
class PaymentTypeRepository extends BaseRepository implements PaymentTypeContract
{
    /**
     * PaymentTypeRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(PaymentType::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('payment_types');
    }

}
