<?php

namespace Src\Broadcasting\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Src\Core\Utils\PushFire;

class WorkerNotify
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  array  $channel
     * @param  array  $data
     */
    public function __construct(protected array $channel = [FcmChannel::class], public array $data)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return $this->channel;
    }

    /**
     * @param  $notifiable
     * @return FcmMessage
     */
    public function toFcm($notifiable): FcmMessage
    {
        return (new PushFire($this->data['title'] ?? '', $this->data['body'] ?? '', $this->data['image'] ?? ''))->fcm($this->data['payload'] ?? '');
    }

    /**
     * @param  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
