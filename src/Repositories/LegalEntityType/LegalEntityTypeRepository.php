<?php

declare(strict_types=1);


namespace Src\Repositories\LegalEntityType;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\LegalEntity\LegalEntityType;

/**
 * Class LegalEntityTypeRepository
 * @package Src\Repositories\LegalEntityType
 */
class LegalEntityTypeRepository extends BaseRepository implements LegalEntityTypeContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(LegalEntityType::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('legal_entity_types');
    }
}
