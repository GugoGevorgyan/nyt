<?php

declare(strict_types=1);


use Src\WebSocket\Controllers\DriverBroadcast;


return [
    'update-coordinate' => [DriverBroadcast::class => 'updateCoordinates', 'guard' => 'drivers_api'],
    'broadway-client' => [DriverBroadcast::class => 'sendClientMessage', 'guard' => 'drivers_api'],
    'notify-viewed' => [DriverBroadcast::class => 'notificationViewed', 'guard' => 'drivers_api'],
    'preorder-accept' => [DriverBroadcast::class => 'acceptPreorderQuestion', 'guard' => 'drivers_api']
];
