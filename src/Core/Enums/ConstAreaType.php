<?php

declare(strict_types=1);


namespace Src\Core\Enums;


use Src\Core\Contracts\Enum;

/**
 * Class ConstAreaType
 * @package Src\Core\Enums
 */
class ConstAreaType extends Enum
{
    private const DESTINATION = 1;
    private const FRANCHISE = 2;
    private const REGION = 3;

    /**
     * @return ConstAreaType
     */
    public static function DESTINATION(): ConstAreaType
    {
        return new self(self::DESTINATION);
    }

    /**
     * @return ConstAreaType
     */
    public static function FRANCHISE(): ConstAreaType
    {
        return new self(self::FRANCHISE);
    }

    /**
     * @return ConstAreaType
     */
    public static function REGION(): ConstAreaType
    {
        return new self(self::REGION);
    }
}
