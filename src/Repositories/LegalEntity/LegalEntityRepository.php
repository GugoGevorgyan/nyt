<?php

declare(strict_types=1);


namespace Src\Repositories\LegalEntity;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\LegalEntity\LegalEntity;

/**
 * Class LegalEntityRepository
 * @package Src\Repositories\LegalEntity
 */
class LegalEntityRepository extends BaseRepository implements LegalEntityContract
{
    /**
     * LegalEntityRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(LegalEntity::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('legal_entities');
    }
}
