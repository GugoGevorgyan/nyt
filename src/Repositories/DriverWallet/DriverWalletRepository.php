<?php

declare(strict_types=1);


namespace Src\Repositories\DriverWallet;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverWallet;

/**
 * Class DriverCashRepository
 * @package Src\Repositories\DriverWallet
 */
class DriverWalletRepository extends BaseRepository implements DriverWalletContract
{
    /**
     * DriverRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverWallet::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('drivers_wallet');
    }
}
