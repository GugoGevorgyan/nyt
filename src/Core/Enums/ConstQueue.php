<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstQueue
 * @package Src\Core\Enums
 * @returns string
 */
class ConstQueue extends Enum
{
    private const TCP = 'tcp';
    private const BASE = 'base';
    private const LONG = 'long';
    private const OBSERVER = 'observer';
    private const SCOUT = 'scout'; // @todo not used

    /**
     * @return ConstQueue
     */
    public static function LONG(): ConstQueue
    {
        return new self(self::LONG);
    }

    /**
     * @return ConstQueue
     */
    public static function TCP(): ConstQueue
    {
        return new self(self::TCP);
    }

    /**
     * @return ConstQueue
     */
    public static function OBSERVER(): ConstQueue
    {
        return new self(self::OBSERVER);
    }

    /**
     * @return ConstQueue
     */
    public static function BASE(): ConstQueue
    {
        return new self(self::BASE);
    }

    /**
     * @return ConstQueue
     */
    public static function SCOUT(): ConstQueue
    {
        return new self(self::SCOUT);
    }
}
