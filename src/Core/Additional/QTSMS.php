<?php

declare(strict_types=1);

namespace Src\Core\Additional;

use Src\Core\Additional\Guzzle as Client;
use Src\Core\Enums\ConstQueue;
use Src\Jobs\Https;

/**
 * Class QTSMS
 * @package Src\Core\Additional
 */
class QTSMS
{
    /**
     * @var string
     */
    protected string $url;
    /**
     * @var Client
     */
    protected Client $client;

    /**
     *
     */
    public function __construct()
    {
        $user = config('nyt.sms_user');
        $password = config('nyt.sms_password');
        $sender = config('nyt.sms_sender');
        $url = config('nyt.app_url');

        $this->url = config('nyt.sms_url');
        $this->url .= "?user=$user&pwd=$password&sadr=$sender";
        $this->client = app(Client::class);
    }

    /**
     * @param $phone
     * @param  string  $text
     * @return void
     */
    public function send($phone, string $text): void
    {
        $this->url .= "&dadr=$phone";
        $this->url .= "&text=$text";

        Https::dispatch($this->url)->onQueue(ConstQueue::BASE()->getValue());
    }
}
