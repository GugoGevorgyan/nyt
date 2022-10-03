<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstRatingPattern
 * @package Src\Core\Enums
 */
class ConstRatingPattern extends Enum
{
    private const LARGE_ORDER_REJECTED = 1;
    private const LARGE_ORDER_ACCEPTED = 2;
    private const NEAR_ORDER_REJECTED = 3;
    private const NEAR_ORDER_ACCEPTED = 4;
    private const FAVORITE_DRIVER_ORDER_REJECTED = 5;
    private const FAVORITE_DRIVER_ORDER_ACCEPTED = 6;
    private const RESET_AFTER_ACCEPT = 7;
    private const ATTACH_ORDER_REJECTED = 8;
    private const ATTACH_ORDER_ACCEPTED = 9;
    private const COMMON_LIST_ACCEPTED = 10;
    private const COMMON_LIST_REJECTED = 11;
    private const ADDRESS_ORDER_ACCEPTED = 12;
    private const ADDRESS_ORDER_REJECTED = 13;
    private const PREORDER_REJECTED = 14;

    /**
     * @return ConstRatingPattern
     */
    public static function LARGE_ORDER_REJECTED(): ConstRatingPattern
    {
        return new self(self::LARGE_ORDER_REJECTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function LARGE_ORDER_ACCEPTED(): ConstRatingPattern
    {
        return new self(self::LARGE_ORDER_ACCEPTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function NEAR_ORDER_REJECTED(): ConstRatingPattern
    {
        return new self(self::NEAR_ORDER_REJECTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function NEAR_ORDER_ACCEPTED(): ConstRatingPattern
    {
        return new self(self::NEAR_ORDER_ACCEPTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function FAVORITE_DRIVER_ORDER_REJECTED(): ConstRatingPattern
    {
        return new self(self::FAVORITE_DRIVER_ORDER_REJECTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function FAVORITE_DRIVER_ORDER_ACCEPTED(): ConstRatingPattern
    {
        return new self(self::FAVORITE_DRIVER_ORDER_ACCEPTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function RESET_AFTER_ACCEPT(): ConstRatingPattern
    {
        return new self(self::RESET_AFTER_ACCEPT);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function ATTACH_ORDER_REJECTED(): ConstRatingPattern
    {
        return new self(self::ATTACH_ORDER_REJECTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function ATTACH_ORDER_ACCEPTED(): ConstRatingPattern
    {
        return new self(self::ATTACH_ORDER_ACCEPTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function COMMON_LIST_ACCEPTED(): ConstRatingPattern
    {
        return new self(self::COMMON_LIST_ACCEPTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function COMMON_LIST_REJECTED(): ConstRatingPattern
    {
        return new self(self::COMMON_LIST_REJECTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function ADDRESS_ORDER_ACCEPTED(): ConstRatingPattern
    {
        return new self(self::ADDRESS_ORDER_ACCEPTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function ADDRESS_ORDER_REJECTED(): ConstRatingPattern
    {
        return new self(self::ADDRESS_ORDER_REJECTED);
    }

    /**
     * @return ConstRatingPattern
     */
    public static function PREORDER_REJECTED(): ConstRatingPattern
    {
        return new self(self::PREORDER_REJECTED);
    }
}
