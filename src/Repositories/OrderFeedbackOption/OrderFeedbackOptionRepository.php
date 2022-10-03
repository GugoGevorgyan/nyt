<?php

declare(strict_types=1);


namespace Src\Repositories\OrderFeedbackOption;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderFeedbackOption;

/**
 * Class CanceledOrderFeedbackTypesRepository
 * @package Src\Repositories\AbortedOrderFeedbackTypes
 */
class OrderFeedbackOptionRepository extends BaseRepository implements OrderFeedbackOptionContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderFeedbackOption::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_feedbacks');
    }
}
