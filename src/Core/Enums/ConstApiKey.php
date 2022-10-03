<?php

declare(strict_types=1);


namespace Src\Core\Enums;


use Src\Core\Contracts\Enum;

/**
 * Class ConstApiKey
 * @package Src\Core\Enums
 */
class ConstApiKey extends Enum
{
    private const Y_GEOCODE = 1;
    private const Y_MATRIX = 2;
    private const Y_ROUTE = 3;
    private const Y_MAP = 4;
    private const GBD = 5;
    private const AIR_FLIGHT = 6;
    private const ACQUIRING_API_TEST = 7;
    private const ACQUIRING_API_PROD = 8;
    private const Y_BUSINESS = 9;

    /**
     * @return ConstApiKey
     */
    public static function Y_GEOCODE(): ConstApiKey
    {
        return new self(self::Y_GEOCODE);
    }

    /**
     * @return ConstApiKey
     */
    public static function Y_ROUTE(): ConstApiKey
    {
        return new self(self::Y_ROUTE);
    }

    /**
     * @return ConstApiKey
     */
    public static function Y_MATRIX(): ConstApiKey
    {
        return new self(self::Y_MATRIX);
    }

    /**
     * @return ConstApiKey
     */
    public static function Y_MAP(): ConstApiKey
    {
        return new self(self::Y_MAP);
    }

    /**
     * @return ConstApiKey
     */
    public static function GBD(): ConstApiKey
    {
        return new self(self::GBD);
    }

    /**
     * @return ConstApiKey
     */
    public static function AIR_FLIGHT(): ConstApiKey
    {
        return new self(self::AIR_FLIGHT);
    }

    /**
     * @return ConstApiKey
     */
    public static function ACQUIRING_API_TEST(): ConstApiKey
    {
        return new self(self::ACQUIRING_API_TEST);
    }

    /**
     * @return ConstApiKey
     */
    public static function ACQUIRING_API_PROD(): ConstApiKey
    {
        return new self(self::ACQUIRING_API_PROD);
    }

    /**
     * @return ConstApiKey
     */
    public static function Y_BUSINESS(): ConstApiKey
    {
        return new self(self::Y_BUSINESS);
    }
}
