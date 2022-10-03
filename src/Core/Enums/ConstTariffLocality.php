<?php

declare(strict_types=1);

namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

class ConstTariffLocality extends Enum
{
    private const CITY = 1;
    private const REGION = 2;
    private const FROM_AIRPORT = 3;
    private const TO_AIRPORT = 4;
    private const FROM_OBJECT = 5;
    private const TO_OBJECT = 6;

    /**
     * @return ConstTariffLocality
     */
    public static function city(): ConstTariffLocality
    {
        return new self(self::CITY);
    }

    /**
     * @return ConstTariffLocality
     */
    public static function region(): ConstTariffLocality
    {
        return new self(self::REGION);
    }

    /**
     * @return ConstTariffLocality
     */
    public static function from_airport(): ConstTariffLocality
    {
        return new self(self::FROM_AIRPORT);
    }

    /**
     * @return ConstTariffLocality
     */
    public static function to_airport(): ConstTariffLocality
    {
        return new self(self::TO_AIRPORT);
    }

    /**
     * @return ConstTariffLocality
     */
    public static function from_object(): ConstTariffLocality
    {
        return new self(self::FROM_OBJECT);
    }

    /**
     * @return ConstTariffLocality
     */
    public static function to_object(): ConstTariffLocality
    {
        return new self(self::TO_OBJECT);
    }
}
