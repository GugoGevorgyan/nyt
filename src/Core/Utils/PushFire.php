<?php

declare(strict_types=1);


namespace Src\Core\Utils;


use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;
use NotificationChannels\Fcm\Resources\Notification;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

/**
 * Class PushFire
 * @package Src\Core
 */
class PushFire
{
    /**
     * PushFire constructor.
     * @param  string  $title
     * @param  string  $body
     * @param  string  $image
     */
    public function __construct(protected string $title = '', protected string $body = '', protected string $image = '')
    {
    }

    /**
     * @param  array  $data
     * @return FcmMessage
     */
    public function fcm(array $data = []): FcmMessage
    {
        return FcmMessage::create()
            ->setNotification(Notification::create()
                ->setTitle($this->title)
                ->setBody($this->body)
                ->setImage($this->image)
            )
            ->setData($data)
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()
                        ->setColor('#FAEBD7')
                        ->setTitle($this->title)
                        ->setBody($this->body)
                        ->setImage($this->image)
                    )
            )
            ->setApns(ApnsConfig::create()
                ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios')->setImage($this->image))
            )->setWebpush(
                WebpushConfig::create()
                    ->setFcmOptions(WebpushFcmOptions::create()->setAnalyticsLabel('analytics_web'))
            );
    }
}
