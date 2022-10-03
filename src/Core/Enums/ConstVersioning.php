<?php

declare(strict_types=1);

namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * GetVersioningRequest Enums
 */
class ConstVersioning extends Enum
{
    private const DEVICE_IOS = 1;
    private const DEVICE_ANDROID = 2;
    private const DEVICE_DESKTOP = 3;

    private const APP_DRIVER = 1;
    private const APP_CLIENT = 2;
    private const APP_TERMINAL = 3;
    private const APP_MECHANIC = 4;

    private const STATE_ALPHA = 1;
    private const STATE_BETA = 2;
    private const STATE_RC = 3;
    private const STATE_STABLE = 4;


    /**
     * ==========================================
     *                  DEVICES
     * ==========================================
     */
    /**
     * @return ConstVersioning
     */
    public static function DEVICE_IOS(): ConstVersioning
    {
        return new self(self::DEVICE_IOS);
    }

    /**
     * @return ConstVersioning
     */
    public static function DEVICE_ANDROID(): ConstVersioning
    {
        return new self(self::DEVICE_ANDROID);
    }

    /**
     * @return ConstVersioning
     */
    public static function DEVICE_DESKTOP(): ConstVersioning
    {
        return new self(self::DEVICE_DESKTOP);
    }


    /**
     * ==========================================
     *                   APP
     * ==========================================
     */
    /**
     * @return ConstVersioning
     */
    public static function APP_DRIVER(): ConstVersioning
    {
        return new self(self::APP_DRIVER);
    }

    /**
     * @return ConstVersioning
     */
    public static function APP_CLIENT(): ConstVersioning
    {
        return new self(self::APP_CLIENT);
    }

    /**
     * @return ConstVersioning
     */
    public static function APP_TERMINAL(): ConstVersioning
    {
        return new self(self::APP_TERMINAL);
    }

    /**
     * @return ConstVersioning
     */
    public static function APP_MECHANIC(): ConstVersioning
    {
        return new self(self::APP_MECHANIC);
    }


    /**
     * ==========================================
     *                  STATE
     * ==========================================
     */
    /**
     * @return ConstVersioning
     */
    public static function STATE_ALPHA(): ConstVersioning
    {
        return new self(self::STATE_ALPHA);
    }

    /**
     * @return ConstVersioning
     */
    public static function STATE_BETA(): ConstVersioning
    {
        return new self(self::STATE_BETA);
    }

    /**
     * @return ConstVersioning
     */
    public static function STATE_RC(): ConstVersioning
    {
        return new self(self::STATE_RC);
    }

    /**
     * @return ConstVersioning
     */
    public static function STATE_STABLE(): ConstVersioning
    {
        return new self(self::STATE_STABLE);
    }
}
