<?php

declare(strict_types=1);


namespace Src\Repositories\FranchiseTransaction;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\FranchiseTransaction;

/**
 * Class FranchiseTransactionRepository
 * @package Src\Repositories\TerminalTransaction
 */
class FranchiseTransactionRepository extends BaseRepository implements FranchiseTransactionContract
{
    /**
     * WaybillRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseTransaction::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_transactions');
    }
}
