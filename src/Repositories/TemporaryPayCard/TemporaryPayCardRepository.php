<?php

declare(strict_types=1);


namespace Src\Repositories\TemporaryPayCard;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\PayCards\TemporaryPayCard;

/**
 * Class TemporaryPayCardRepository
 * @package Src\Repositories\TemporaryPayCard
 */
class TemporaryPayCardRepository extends BaseRepository implements TemporaryPayCardContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(TemporaryPayCard::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('temporary_pay_cards');
    }
}
