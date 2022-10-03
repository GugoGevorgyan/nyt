<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstDriverType
 */
class ConstDriverType extends Enum
{
    private const TENANT = 1;
    private const AGGREGATOR = 2;
    private const ROLL = 3;
    private const CORPORATE = 4;

    /**
     * @return ConstDriverType
     */
    public static function CORPORATE(): ConstDriverType
    {
        return new self(self::CORPORATE);
    }

    /**
     * @return ConstDriverType
     */
    public static function TENANT(): ConstDriverType
    {
        return new self(self::TENANT);
    }

    /**
     * @return ConstDriverType
     */
    public static function ROLL(): ConstDriverType
    {
        return new self(self::ROLL);
    }

    /**
     * @return ConstDriverType
     */
    public static function AGGREGATOR(): ConstDriverType
    {
        return new self(self::AGGREGATOR);
    }

}
