<?php

declare(strict_types=1);

namespace Src\Repositories\Conversation;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Chat\OrderConversation;

/**
 *
 */
class ConversationRepository extends BaseRepository implements ConversationContract
{
    /**
     * ComplaintStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderConversation::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('order_conversations');
    }
}
