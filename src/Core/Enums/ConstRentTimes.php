<?php

declare(strict_types=1);


namespace Src\Core\Enums;


use Src\Core\Contracts\Enum;

/**
 * Class ConstApiKey
 * @package Src\Core\Enums
 */
class ConstRentTimes extends Enum
{
    private const HOUR_1 = 1;
    private const HOUR_2 = 2;
    private const HOUR_4 = 4;
    private const HOUR_6 = 6;
    private const HOUR_8 = 8;
    private const HOUR_10 = 10;
    private const HOUR_12 = 12;
    private const HOUR_24 = 24;

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_1(): ConstRentTimes
    {
        return new self(self::HOUR_1);
    }

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_2(): ConstRentTimes
    {
        return new self(self::HOUR_2);
    }

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_4(): ConstRentTimes
    {
        return new self(self::HOUR_4);
    }

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_6(): ConstRentTimes
    {
        return new self(self::HOUR_6);
    }

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_8(): ConstRentTimes
    {
        return new self(self::HOUR_8);
    }

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_10(): ConstRentTimes
    {
        return new self(self::HOUR_10);
    }

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_12(): ConstRentTimes
    {
        return new self(self::HOUR_12);
    }

    /**
     * @return ConstRentTimes
     */
    public static function HOUR_24(): ConstRentTimes
    {
        return new self(self::HOUR_24);
    }
}
