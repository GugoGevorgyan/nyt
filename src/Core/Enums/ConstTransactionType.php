<?php

declare(strict_types=1);


namespace Src\Core\Enums;


use Src\Core\Contracts\Enum;

/**
 * Class ConstTransactionType
 * @package Src\Core\Enums
 */
class ConstTransactionType extends Enum
{
    private const BALANCE = 1;
    private const DEBT = 2;
    private const WAYBILL = 3;
    private const ORDER = 4;
    private const CRASH = 5;

    /**
     * @return ConstTransactionType
     */
    public static function BALANCE(): ConstTransactionType
    {
        return new self(self::BALANCE);
    }

    /**
     * @return ConstTransactionType
     */
    public static function DEBT(): ConstTransactionType
    {
        return new self(self::DEBT);
    }

    /**
     * @return ConstTransactionType
     */
    public static function WAYBILL(): ConstTransactionType
    {
        return new self(self::WAYBILL);
    }

    /**
     * @return ConstTransactionType
     */
    public static function ORDER(): ConstTransactionType
    {
        return new self(self::ORDER);
    }

    /**
     * @return ConstTransactionType
     */
    public static function CRASH(): ConstTransactionType
    {
        return new self(self::CRASH);
    }
}
