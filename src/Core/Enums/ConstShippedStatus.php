<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use ReflectionException;
use Src\Core\Contracts\Enum;

/**
 * Class ConstShippedStatus
 * @package Src\Core\Enums
 */
class ConstShippedStatus extends Enum
{
    private const PRE_PENDING = 1;
    private const PRE_ACCEPTED = 2;
    private const PRE_REJECTED = 3;
    private const PRE_CANCELED = 4;
    private const PRE_MANUAL = 5;
    private const PRE_UNPIN = 6;

    /**
     * @return ConstShippedStatus
     */
    public static function pre_pending(): ConstShippedStatus
    {
        return new self(self::PRE_PENDING);
    }

    /**
     * @return ConstShippedStatus
     */
    public static function pre_accepted(): ConstShippedStatus
    {
        return new self(self::PRE_ACCEPTED);
    }

    /**
     * @return ConstShippedStatus
     */
    public static function pre_rejected(): ConstShippedStatus
    {
        return new self(self::PRE_REJECTED);
    }

    /**
     * @return ConstShippedStatus
     */
    public static function pre_canceled(): ConstShippedStatus
    {
        return new self(self::PRE_CANCELED);
    }

    /**
     * @return ConstShippedStatus
     */
    public static function pre_manual(): ConstShippedStatus
    {
        return new self(self::PRE_MANUAL);
    }

    /**
     * @return ConstShippedStatus
     */
    public static function pre_unpin(): ConstShippedStatus
    {
        return new self(self::PRE_UNPIN);
    }
}
