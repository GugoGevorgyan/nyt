<?php

declare(strict_types=1);


namespace Src\Core\Enums;


use Src\Core\Contracts\Enum;

/**
 * Class ConsTariffRounding
 * @package Src\Core\Enums
 */
class ConsTariffRounding extends Enum
{
    private const UP_STAIRS = 1;
    private const DAWN_WARDS = 2;
    private const NOT_ROUND = 3;

    /**
     * @return ConsTariffRounding
     */
    public static function UP_STAIRS(): ConsTariffRounding
    {
        return new self(self::UP_STAIRS);
    }

    /**
     * @return ConsTariffRounding
     */
    public static function DAWN_WARDS(): ConsTariffRounding
    {
        return new self(self::DAWN_WARDS);
    }

    /**
     * @return ConsTariffRounding
     */
    public static function NOT_ROUND(): ConsTariffRounding
    {
        return new self(self::NOT_ROUND);
    }
}
