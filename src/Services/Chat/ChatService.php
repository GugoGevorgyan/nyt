<?php

declare(strict_types=1);


namespace Src\Services\Chat;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ServiceEntity\BaseService;
use Src\Broadcasting\Broadcast\Client\BroadwayDriverTalk;
use Src\Broadcasting\Broadcast\Driver\BroadwayClientTalk;
use Src\Models\Client\Client;
use Src\Models\Driver\Driver;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Conversation\ConversationContract;
use Src\Repositories\ConversationTalk\ConversationTalkContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * Class ChatService
 * @package Src\Services\WorkerChat
 */
class ChatService extends BaseService implements ChatServiceContract
{
    /**
     * ChatService constructor.
     * @param  ClientContract  $clientContract
     * @param  DriverContract  $driverContract
     */
    public function __construct(
        protected ClientContract $clientContract,
        protected DriverContract $driverContract,
        protected SystemWorkerContract $systemWorkerContract,
        protected ConversationTalkContract $conversationTalkContract,
        protected ConversationContract $conversationContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getRooms()
    {
        $data = user()
            ->chat_participants()
            ->with(
                [
                    'room' => static function ($room_query) {
                        $room_query->with(
                            [
                                'participants' => static function ($query) {
                                    $query->with('userable');
                                }
                            ]
                        );
                    }
                ]
            )
            ->get()
            ->map(
                static function ($item) {
                    return $item->room;
                }
            )
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getConversation($room_id, $user_id)
    {
        $room_accept = $this->participantContract->where('room_id', '=', $room_id)->where('userable_id', '=', $user_id)->exists();

        if (!$room_accept) {
            return null;
        }

        $messages = $this->messageContract
            ->findWhere(['room_id', '=', $room_id], ['message_id', 'room_id', 'senderable_type', 'senderable_id', 'message', 'unix_time'])
            ->sortBy('unix_time');

        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function messageSave($sender_id, $room_id, $message_data)
    {
        $save = user()->chat_sender()->create(['room_id' => $room_id, 'message' => $message_data]);

        if (!$save) {
            return null;
        }

        return $this->participantContract->findWhere(['room_id', '=', $room_id], ['room_id', 'userable_id']);
    }


    /////////////////////////////////////////CLIENT DRIVER CONVERSATION///////////////////////////////////////

    /**
     * Client To Driver
     * @param $client_id
     * @param $msg
     * @return void
     */
    public function clientBroadwayDriver($client_id, string $msg = ''): void
    {
        $client = $this->clientContract->find($client_id);
        $this->conversationClient($client);

        BroadwayClientTalk::broadcast($client->current_order_driver, $msg);

        $this->conversationTalkContract->create([
            'order_conversation_id' => $client->conversation_id,
            'message' => $msg,
            'sender_id' => $client_id,
            'sender_type' => $client->getMap()
        ]);

        $client = null;
    }

    /**
     * @param  Client  $client
     * @return Client
     */
    protected function conversationClient(&$client): Client
    {
        $client->load(
            [
                'current_order' => fn(HasOne $query) => $query->select(['orders.order_id', 'client_id']),
                'current_order_driver' => fn(HasOneDeep $query) => $query->select(['drivers.driver_id', 'phone', 'current_franchise_id', 'car_id']),
                'conversation' => fn(MorphMany $query) => $query
                    ->where('order_id', '=', $client->current_order->order_id)
                    ->where('driver_id', '=', $client->current_order_driver->driver_id)
                    ->select(['order_conversation_id', 'order_id', 'driver_id', 'client_id', 'client_type'])
            ]
        );

        if ($client->conversation->count()) {
            $client->conversation_id = $client->conversation->first()->order_conversation_id;
        } else {
            $conversation = $this->conversationContract->create([
                'order_id' => $client->current_order->order_id,
                'client_id' => $client->client_id,
                'driver_id' => $client->current_order_driver->driver_id,
                'sender_id' => $client->client_id,
                'sender_type' => $client->getMap(),
            ]);

            $client->conversation_id = $conversation->order_conversation_id;
        }

        return $client;
    }

    /**
     * Driver To Client
     * @inheritDoc
     */
    public function driverBroadwayClient($driver_id, string $msg = ''): void
    {
        $driver = $this->driverContract->find($driver_id);
        $this->conversationDriver($driver);

        BroadwayDriverTalk::broadcast($driver->order_client, ['text' => $msg, 'sender' => 'driver']);
        $this->conversationTalkContract->create([
            'order_conversation_id' => $driver->conversation_id,
            'message' => $msg,
            'sender_id' => $driver_id,
            'sender_type' => $driver->getMap()
        ]);

        $driver = null;
    }

    /**
     * @param  Driver  $driver
     * @return Driver
     */
    protected function conversationDriver(&$driver): Driver
    {
        $driver->load(
            [
                'current_order' => fn(HasOneDeep $query) => $query->select(['orders.order_id', 'client_id']),
                'order_client' => fn(HasOneDeep $query) => $query->select(['clients.client_id', 'phone']),
                'conversation' => fn(HasMany $query) => $query
                    ->where('order_id', '=', $driver->current_order->order_id)
                    ->select(['order_conversation_id', 'order_id', 'driver_id', 'client_id', 'client_type'])
            ]
        );

        if ($driver->conversation->count()) {
            $driver->conversation_id = $driver->conversation->first()->order_conversation_id;
        } else {
            $conversation = $this->conversationContract->create([
                'order_id' => $driver->current_order->order_id,
                'client_id' => $driver->client_id,
                'driver_id' => $driver->current_order_driver->driver_id,
                'sender_id' => $driver->client_id,
                'sender_type' => $driver->getMap(),
            ]);
            $driver->conversation_id = $conversation->order_conversation_id;
        }

        return $driver;
    }
    /////////////////////////////////////////CLIENT DRIVER CONVERSATION END///////////////////////////////////////
}
