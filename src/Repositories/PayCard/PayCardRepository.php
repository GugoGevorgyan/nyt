<?php

declare(strict_types=1);


namespace Src\Repositories\PayCard;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\PayCards\PayCard;

/**
 * Class PayCardRepository
 * @package Src\Repositories\PayCard
 */
class PayCardRepository extends BaseRepository implements PayCardContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(PayCard::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('pay_cards');
    }
}
