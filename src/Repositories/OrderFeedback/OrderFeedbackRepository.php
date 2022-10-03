<?php

declare(strict_types=1);


namespace Src\Repositories\OrderFeedback;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderFeedback;

/**
 * Class OrderFeedbackRepository
 * @package Src\Repositories\OrderFeedback
 */
class OrderFeedbackRepository extends BaseRepository implements OrderFeedbackContract
{
    /**
     * OrderFeedbackRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderFeedback::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_feedbacks');
    }
}
