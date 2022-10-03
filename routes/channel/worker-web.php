<?php

declare(strict_types=1);

use Src\Broadcasting\Channels\WorkerChannel;
use Src\Broadcasting\Channels\WorkerDispatcher;
use Src\Broadcasting\Channels\WorkerMessageChannel;
use Src\Broadcasting\Channels\WorkerOperator;
use Src\Core\Enums\ConstChannels;


Broadcast::channel(ConstChannels::worker_web()->getValue().'-worker.{worker_id}.{franchise_id}', WorkerChannel::class);

Broadcast::channel(ConstChannels::worker_web()->getValue().'-worker-chat.{worker_id}.{franchise_id}', WorkerMessageChannel::class);

Broadcast::channel(ConstChannels::worker_web()->getValue().'-worker-operator.{worker_id}.{franchise_id}', WorkerOperator::class);

Broadcast::channel(ConstChannels::worker_web()->getValue().'-worker-dispatcher.{worker_id}.{franchise_id}', WorkerDispatcher::class);
