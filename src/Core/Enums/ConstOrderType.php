<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstOrderType
 * @package Src\Core\Enums
 */
class ConstOrderType extends Enum
{
    private const CLIENT_ORDER = 1;
    private const CLIENT_ORDER_BY_COMPANY = 2;
    private const COMPANY_TO_CLIENT = 3;
    private const OTHER = 4;

    /**
     * @return ConstOrderType
     */
    public static function CLIENT_ORDER(): ConstOrderType
    {
        return new self(self::CLIENT_ORDER);
    }

    /**
     * @return ConstOrderType
     */
    public static function CLIENT_ORDER_BY_COMPANY(): ConstOrderType
    {
        return new self(self::CLIENT_ORDER_BY_COMPANY);
    }

    /**
     * @return ConstOrderType
     */
    public static function COMPANY_TO_CLIENT(): ConstOrderType
    {
        return new self(self::COMPANY_TO_CLIENT);
    }

    /**
     * @return ConstOrderType
     */
    public static function OTHER(): ConstOrderType
    {
        return new self(self::OTHER);
    }
}
