<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstDeviceType
 * @package Src\Core\Enums
 */
class ConstDeviceType extends Enum
{
    private const MOBILE = 'mobile';
    private const MOBILE_BROWSER = 'mobile_browser';
    private const BROWSER = 'browser';

    /**
     * @return ConstDeviceType
     */
    public static function MOBILE(): ConstDeviceType
    {
        return new self(self::MOBILE);
    }

    /**
     * @return ConstDeviceType
     */
    public static function MOBILE_BROWSER(): ConstDeviceType
    {
        return new self(self::MOBILE_BROWSER);
    }

    /**
     * @return ConstDeviceType
     */
    public static function BROWSER(): ConstDeviceType
    {
        return new self(self::BROWSER);
    }
}
