<?php

declare(strict_types=1);


namespace Src\Repositories\DriverDebt;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Debt\Debt;

/**
 * Class DebtRepository
 * @package Src\Repositories\Debt
 */
class DebtRepository extends BaseRepository implements DebtContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Debt::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('debts');
    }
}
