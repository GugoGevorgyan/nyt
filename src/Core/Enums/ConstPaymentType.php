<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstPaymentType
 * @package Src\Core\Enums
 */
class ConstPaymentType extends Enum
{
    private const CASH = 1;
    private const PAY_COMPANY = 2;
    private const CREDIT_CARD = 3;

    /**
     * @return ConstPaymentType
     */
    public static function CASH(): ConstPaymentType
    {
        return new self(self::CASH);
    }

    /**
     * @return ConstPaymentType
     */
    public static function PAY_COMPANY(): ConstPaymentType
    {
        return new self(self::PAY_COMPANY);
    }

    /**
     * @return ConstPaymentType
     */
    public static function CREDIT_CARD(): ConstPaymentType
    {
        return new self(self::CREDIT_CARD);
    }
}
