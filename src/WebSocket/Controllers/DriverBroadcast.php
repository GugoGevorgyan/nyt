<?php

declare(strict_types=1);


namespace Src\WebSocket\Controllers;


use Illuminate\Http\Request;
use Src\Core\Enums\ConstRedis;
use Src\Core\Socket\Broadcast\BaseBroadcast;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Notiication\NotificationContract;
use Src\Services\Chat\ChatServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Order\OrderServiceContract;

/**
 * Class DriverBroadcast
 * @package Src\WebSocket\Controllers
 */
class DriverBroadcast extends BaseBroadcast
{
    /**
     * Create a new channel instance.
     *
     * @param  DriverServiceContract  $driverService
     * @param  ChatServiceContract  $chatService
     * @param  OrderServiceContract  $orderService
     * @param  NotificationContract  $notificationContract
     * @param  DriverContract  $driverContract
     */
    public function __construct(
        protected DriverServiceContract $driverService,
        protected ChatServiceContract $chatService,
        protected OrderServiceContract $orderService,
        protected NotificationContract $notificationContract,
        protected DriverContract $driverContract,
    ) {
    }

    /**
     * @param  Request  $request
     */
    public function updateCoordinates(Request $request): void
    {
        $this->driverService->updateCurrentCoordinates(
            $request->data->lat,
            $request->data->lut,
            $request->data->azimuth,
            $request->data->speed,
            $request->user->driver_id
        );
    }

    /**
     * @param  Request  $request
     */
    public function sendClientMessage(Request $request): void
    {
        $user = $request->get('user');
        $data = $request->get('data');

        $this->chatService->driverBroadwayClient($user->driver_id, $data->text);
    }

    /**
     * @param  Request  $request
     */
    public function notificationViewed(Request $request): void
    {
        $this->notificationContract
            ->where('group_number', '=', $request->notf_id)
            ->where('annonciator_id', '=', $request->driver_id)
            ->where('annonciator_type', '=', $this->driverContract->getMap())
            ->updateSet(['viewed' => true]);
    }

    /**
     *
     */
    public function acceptPreorderQuestion(Request $request): void
    {
        $r_connect = redis();

        $user = $request->get('user');
        $data = $request->get('data');
        $order_data = $r_connect->hMGet(ConstRedis::driver_preorder_question($user->driver_id), ['order_id', 'started']);

        if (!$order_data && !($order_data[0] || $order_data[1])) {
            return;
        }

        $order_id = (int)$order_data[0];
        $started = $order_data[1];

        $r_connect->hDel(ConstRedis::driver_preorder_question($user->driver_id), ...['order_id', 'started']);

        $this->driverService->questionPreorderAccept($user->driver_id, $order_id, $started, $data->accept);
    }
}
