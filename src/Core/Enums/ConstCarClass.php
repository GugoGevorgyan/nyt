<?php

declare(strict_types=1);


namespace Src\Core\Enums;


use Src\Core\Contracts\Enum;

/**
 * Class ConstCarClass
 * @package Src\Core\Enums
 */
class ConstCarClass extends Enum
{
    private const ECONOMY = 1;
    private const COMFORT = 2;
    private const BUSINESS = 3;
    private const BUSINESS_PLUS = 4;

    /**
     * @return ConstCarClass
     */
    public static function economy(): ConstCarClass
    {
        return new self(self::ECONOMY);
    }

    /**
     * @return ConstCarClass
     */
    public static function comfort(): ConstCarClass
    {
        return new self(self::COMFORT);
    }

    /**
     * @return ConstCarClass
     */
    public static function business(): ConstCarClass
    {
        return new self(self::BUSINESS);
    }

    /**
     * @return ConstCarClass
     */
    public static function businessPlus(): ConstCarClass
    {
        return new self(self::BUSINESS_PLUS);
    }
}
