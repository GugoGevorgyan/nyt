<?php

declare(strict_types=1);

use Src\Broadcasting\Channels\ClientWebBaseAuthChannel;
use Src\Core\Enums\ConstChannels;


Broadcast::channel(ConstChannels::client_web()->getValue().'-base.{client_id}.{phone}', ClientWebBaseAuthChannel::class);
