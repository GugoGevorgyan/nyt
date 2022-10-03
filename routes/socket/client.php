<?php

declare(strict_types=1);


use Src\WebSocket\Controllers\ClientBroadcast;


return [
    'broadway-driver' => [ClientBroadcast::class => 'sendMessageToDriver', 'guard' => 'clients_web'],
    'show-my-cord' => [ClientBroadcast::class => 'showMyCordToDriver', 'guard' => 'clients_web'],
];
