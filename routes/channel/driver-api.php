<?php

declare(strict_types=1);

use Src\Broadcasting\Channels\DriverApiBaseAuthChannel;
use Src\Core\Enums\ConstChannels;


Broadcast::channel(ConstChannels::driver_api()->getValue().'-base-driver-channel.{driver_id}.{phone}.{car_id}.{franchise_id}', DriverApiBaseAuthChannel::class);
