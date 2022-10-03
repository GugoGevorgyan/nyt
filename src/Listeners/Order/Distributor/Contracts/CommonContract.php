<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Distributor\Contracts;

/**
 *
 */
interface CommonContract
{
    /**
     * Search Driver pass order and add common list
     */
    public function commonList(): void;

    /**
     * @param $common
     * @param $drivers
     * @param $passed
     */
    public function tikTakCommon($common, $drivers, $passed): void;
}
