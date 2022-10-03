<?php

declare(strict_types=1);

namespace Src\Core\Socket\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Ponged
{
    use Dispatchable;
    use SerializesModels;
}
