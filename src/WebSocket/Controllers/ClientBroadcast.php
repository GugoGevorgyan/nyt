<?php

declare(strict_types=1);


namespace Src\WebSocket\Controllers;


use Illuminate\Http\Request;
use Src\Core\Socket\Broadcast\BaseBroadcast;
use Src\Services\Chat\ChatServiceContract;
use Src\Services\Client\ClientServiceContract;

/**
 * Class ClientBroadcast
 * @package Src\WebSocket\Controllers
 */
class ClientBroadcast extends BaseBroadcast
{

    /**
     * ClientBroadcast constructor.
     * @param  ChatServiceContract  $chatService
     * @param  ClientServiceContract  $clientService
     */
    public function __construct(protected ChatServiceContract $chatService, protected ClientServiceContract $clientService)
    {
    }

    /**
     * @param  Request  $request
     */
    public function sendMessageToDriver(Request $request): void
    {
        $user = $request->get('user');
        $data = $request->get('data');

        $this->chatService->clientBroadwayDriver($user->client_id, $data->text);
    }

    /**
     * @param  Request  $request
     */
    public function showMyCordToDriver(Request $request): void
    {
        $user = $request->get('user');
        $data = $request->get('data');

        $this->clientService->showMyCordsInOrder($user->{$user->getKeyName()}, $data->show, isset($data->cords) ? (array)$data->cords : []);
    }
}
