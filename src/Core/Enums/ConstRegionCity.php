<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use ReflectionException;
use Src\Core\Contracts\Enum;

/**
 * Class ConstRegionCity
 * @package Src\Core\Enums
 */
class ConstRegionCity extends Enum
{
    private const MOSCOW = 'Moscow';
    private const MOSCOW_REGION = 'Moscow Region';

    /**
     * @return ConstRegionCity
     * @throws ReflectionException
     */
    public static function MOSCOW(): ConstRegionCity
    {
        return new self(self::MOSCOW);
    }

    /**
     * @return ConstRegionCity
     * @throws ReflectionException
     */
    public static function MOSCOW_REGION(): ConstRegionCity
    {
        return new self(self::MOSCOW_REGION);
    }
}
