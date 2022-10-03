<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Distributor\Contracts;

use Src\Core\Contracts\Decorator;

interface DecorateInterface extends Decorator
{
    /**
     * @param ...$params
     */
    public function search(...$params): void;
}
