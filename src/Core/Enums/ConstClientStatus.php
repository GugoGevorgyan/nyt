<?php

declare(strict_types=1);


namespace Src\Core\Enums;


use Src\Core\Contracts\Enum;

/**
 * Class ConstClientStatus
 * @package Src\Core\Enums
 */
class ConstClientStatus extends Enum
{
    private const STATELESS = 1;
    private const PENDING_SEARCH = 2;
    private const ACCEPT_ORDER = 3;
    private const EXPECT_TAXI = 4;
    private const WAITING_TAXI = 5;
    private const ORDER = 6;
    private const ASSESSMENT = 7;

    /**
     * @return ConstClientStatus
     */
    public static function stateless(): ConstClientStatus
    {
        return new self(self::STATELESS);
    }

    /**
     * @return ConstClientStatus
     */
    public static function pending_search(): ConstClientStatus
    {
        return new self(self::PENDING_SEARCH);
    }

    /**
     * @return ConstClientStatus
     */
    public static function accept_order(): ConstClientStatus
    {
        return new self(self::ACCEPT_ORDER);
    }

    /**
     * @return ConstClientStatus
     */
    public static function expect_taxi(): ConstClientStatus
    {
        return new self(self::EXPECT_TAXI);
    }

    /**
     * @return ConstClientStatus
     */
    public static function waiting_taxi(): ConstClientStatus
    {
        return new self(self::WAITING_TAXI);
    }

    /**
     * @return ConstClientStatus
     */
    public static function order(): ConstClientStatus
    {
        return new self(self::ORDER);
    }

    /**
     * @return ConstClientStatus
     */
    public static function assessment(): ConstClientStatus
    {
        return new self(self::ASSESSMENT);
    }
}
