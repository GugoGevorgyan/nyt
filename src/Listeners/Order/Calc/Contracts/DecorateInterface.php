<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Calc\Contracts;

use Src\Core\Contracts\Decorator;
use Src\Models\Order\OrderProcess;

interface DecorateInterface extends Decorator
{
    /**
     * @param  array  $last_cords
     * @param  OrderProcess  $process
     */
    public function calculate(array $last_cords, OrderProcess $process): void;
}
