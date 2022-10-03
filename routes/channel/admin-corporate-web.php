<?php

declare(strict_types=1);

use Src\Broadcasting\Channels\AdminCorporateWebChannel;
use Src\Core\Enums\ConstChannels;


Broadcast::channel(ConstChannels::admin_corporate_web()->getValue().'-base.{admin_id}.{company_id}.{franchise_id}', AdminCorporateWebChannel::class);
