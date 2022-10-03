<?php

declare(strict_types=1);


namespace Src\Repositories\DebtRepayment;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Terminal\DebtRepayment;

/**
 * Class DebtRepaymentRepository
 * @package Src\Repositories\DebtRepayment
 */
class DebtRepaymentRepository extends BaseRepository implements DebtRepaymentContract
{
    /**
     * CountryRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DebtRepayment::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('debt_repayment');
    }
}
