<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Src\Broadcasting\Channels\ClientApiBaseAuthChannel;
use Src\Core\Enums\ConstChannels;


Broadcast::channel(ConstChannels::client_api()->getValue().'-base.{client_id}.{phone}', ClientApiBaseAuthChannel::class);
