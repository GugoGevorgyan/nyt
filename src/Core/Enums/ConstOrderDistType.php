<?php

declare(strict_types=1);

namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 *
 */
class ConstOrderDistType extends Enum
{
    private const AUTOMATIC = 1;
    private const MANUAL = 2;
    private const COMMON = 3;

    /**
     * @return ConstOrderDistType
     */
    public static function automatic(): ConstOrderDistType
    {
        return new self(self::AUTOMATIC);
    }

    /**
     * @return ConstOrderDistType
     */
    public static function manual(): ConstOrderDistType
    {
        return new self(self::MANUAL);
    }

    /**
     * @return ConstOrderDistType
     */
    public static function common(): ConstOrderDistType
    {
        return new self(self::COMMON);
    }
}
