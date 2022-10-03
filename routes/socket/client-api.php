<?php

declare(strict_types=1);

use Src\WebSocket\Controllers\ClientMobileBroadcast;


return [
    'broadway-driver' => [ClientMobileBroadcast::class => 'sendMessageToDriver', 'guard' => 'clients_api'],
    'show-my-cord' => [ClientMobileBroadcast::class => 'showMyCordToDriver', 'guard' => 'clients_api'],
    'notify-viewed' => [ClientMobileBroadcast::class => 'notificationViewed', 'guard' => 'clients_api']
];
