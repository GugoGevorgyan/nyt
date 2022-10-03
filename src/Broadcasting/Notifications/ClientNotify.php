<?php

declare(strict_types=1);

namespace Src\Broadcasting\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Src\Core\Contracts\NotifyContract;
use Src\Core\Utils\PushFire;

/**
 *
 */
class ClientNotify extends NotifyContract
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param $notificationId
     * @param  array  $data
     * @param  array  $channel
     */
    public function __construct(public $notificationId, public array $data = [], protected array $channel = [FcmChannel::class])
    {
    }

    /**
     * @param  $notifiable
     * @return FcmMessage
     */
    public function toFcm($notifiable): FcmMessage
    {
        return (new PushFire($this->data['title'] ?? '', $this->data['body'] ?? '', $this->data['image'] ?? ''))
            ->fcm(['notification_id' => $this->notificationId, 'payload' => $this->data['payload'] ?? ''] ?? []);
    }
}
