<?php

declare(strict_types=1);

namespace Src\Core\Socket\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Closed
{
    use Dispatchable;
    use SerializesModels;
}
