<?php

declare(strict_types=1);

namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

class ConstQueueUuid extends Enum
{
    private const ORDER_DISTRIBUTE = 'queue:order_distribute';
    private const PREORDER_START = 'queue:preorder_start';

    /**
     * @return ConstQueueUuid
     */
    public static function order_distribute(): ConstQueueUuid
    {
        return new self(self::ORDER_DISTRIBUTE);
    }

    /**
     * @return ConstQueueUuid
     */
    public static function preorder_start(): ConstQueueUuid
    {
        return new self(self::PREORDER_START);
    }
}
