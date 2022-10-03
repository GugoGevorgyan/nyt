<?php

declare(strict_types=1);

use Src\Broadcasting\Channels\BeforeClientBaseChannel;
use Src\Core\Enums\ConstChannels;


Broadcast::channel(ConstChannels::before_client_web()->getValue().'-base.{before_client_id}.{hash}', BeforeClientBaseChannel::class);
