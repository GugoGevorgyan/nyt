<?php

declare(strict_types=1);


namespace Src\Repositories\ConversationTalk;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Chat\OrderConversation;
use Src\Models\Chat\OrderConversationTalk;

/**
 * Class ConversationTalkRepository
 * @package Src\Repositories\ConversationTalk
 */
class ConversationTalkRepository extends BaseRepository implements ConversationTalkContract
{
    /**
     * ComplaintStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderConversationTalk::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('order_conversations_talks');
    }
}
