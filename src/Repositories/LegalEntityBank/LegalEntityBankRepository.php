<?php

declare(strict_types=1);


namespace Src\Repositories\LegalEntityBank;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\LegalEntity\LegalEntityBank;

/**
 * Class LegalEntityBankRepository
 * @package Src\Repositories\LegalEntityBank
 */
class LegalEntityBankRepository extends BaseRepository implements LegalEntityBankContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(LegalEntityBank::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('legal_entity_banks');
    }
}
