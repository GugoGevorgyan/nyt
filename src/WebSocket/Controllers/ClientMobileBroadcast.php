<?php

declare(strict_types=1);


namespace Src\WebSocket\Controllers;


use Illuminate\Http\Request;
use Src\Core\Socket\Broadcast\BaseBroadcast;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Notiication\NotificationContract;
use Src\Services\Chat\ChatServiceContract;
use Src\Services\Client\ClientServiceContract;

/**
 * Class ClientMobileBroadcast
 * @package Src\WebSocket\Controllers
 */
class ClientMobileBroadcast extends BaseBroadcast
{
    /**
     * ClientBroadcast constructor.
     * @param  ChatServiceContract  $chatService
     * @param  ClientServiceContract  $clientService
     * @param  NotificationContract  $notificationContract
     */
    public function __construct(
        protected ChatServiceContract $chatService,
        protected ClientServiceContract $clientService,
        protected NotificationContract $notificationContract,
        protected ClientContract $clientContract,
    ) {
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

    /**
     * @param  Request  $request
     */
    public function notificationViewed(Request $request): void
    {
        $this->notificationContract
            ->where('group_number', '=', $request->notf_id)
            ->where('annonciator_id', '=', $request->client_id)
            ->where('annonciator_type', '=', $this->clientContract->getMap())
            ->updateSet(['viewed' => true]);
    }
}
