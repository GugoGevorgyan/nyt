<?php
declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Src\Broadcasting\Broadcast\Worker\SendChatMessageEvent;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\GetConversationRequest;
use Src\Http\Requests\MessageSendRequest;
use Src\Services\Chat\ChatServiceContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

/**
 * Class ChatController
 * @package Src\Http\Controllers\SystemWorker
 */
class ChatController extends Controller
{
    /**
     * @var ChatServiceContract
     */
    protected ChatServiceContract $chatService;

    /**
     * ChatController constructor.
     * @param  ChatServiceContract  $workerChatService
     */
    public function __construct(ChatServiceContract $workerChatService)
    {
        $this->chatService = $workerChatService;
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getRooms()
    {
        $participants = $this->chatService->getRooms();

        return $participants?
            response($participants):
            response(['message' => 'Room Not Found']);
    }

    /**
     * @param $room_id
     * @param  GetConversationRequest  $request
     * @return ResponseFactory|Response
     */
    public function getConversation($room_id, GetConversationRequest $request)
    {
        $conversation = $this->chatService->getConversation($room_id, $request->user()->{$request->user()->getKeyName()});

        if (!$conversation) {
            return response(['message' => 'Server Error', 'status' => 'FAILED'], 500);
        }

        return response(['message' => 'Conversation data', 'status' => 'OK', $conversation]);
    }

    /**
     * @param  MessageSendRequest  $request
     * @return ResponseFactory|Response
     */
    public function messageSend(MessageSendRequest $request)
    {
        if (!$request->validated()) {
            return response(['message' => 'Data Invalid', 'status' => 'FAILED'], 422);
        }

        $save_msg = $this->chatService->messageSave(
            $request->user()->{$request->user()->getKeyName()},
            $request->room_id,
            $request->message_data
        );

        if (!$save_msg) {
            return response(['message' => 'ERROR during save Message', 'status' => 'FAILED'], 500);
        }

        foreach ($save_msg as $participant) {
            broadcast(
                new SendChatMessageEvent(
                    $request->user()->{$request->user()->getKeyName()},
                    $participant->userable_id,
                    $request->room_id,
                    $request->message_data
                )
            );
        }

        return response(['message' => 'Message saved', 'status' => 'OK']);
    }
}
