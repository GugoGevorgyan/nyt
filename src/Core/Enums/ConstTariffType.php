<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use ReflectionException;
use Src\Core\Contracts\Enum;

/**
 * Class ConstTariffType
 * @package Src\Core\Enums
 */
class ConstTariffType extends Enum
{
    private const MINUTE = 1;
    private const KILOMETER = 2;
    private const KILOMETER_AND_MINUTE = 3;
    private const DESTINATION = 4;
    private const RENTAL = 5;

    /**
     * @return ConstTariffType
     */
    public static function MINUTE(): ConstTariffType
    {
        return new self(self::MINUTE);
    }

    /**
     * @return ConstTariffType
     */
    public static function KM(): ConstTariffType
    {
        return new self(self::KILOMETER);
    }

    /**
     * @return ConstTariffType
     */
    public static function KM_AND_MIN(): ConstTariffType
    {
        return new self(self::KILOMETER_AND_MINUTE);
    }

    /**
     * @return ConstTariffType
     */
    public static function DESTINATION(): ConstTariffType
    {
        return new self(self::DESTINATION);
    }

    /**
     * @return ConstTariffType
     */
    public static function RENTAL(): ConstTariffType
    {
        return new self(self::RENTAL);
    }
}
