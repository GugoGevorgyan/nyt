<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConsTerminalPwd
 * @package Src\Core\Enums
 */
class ConsTerminalPwd extends Enum
{
    private const FIRST = 'nyt_transit_terminal__(@First)../2020';
    private const SECOND = 'nyt_transit_terminal__(@Second)../2020';
    private const THREAD = 'nyt_transit_terminal__(@Thread)../2020';

    /**
     * @return ConsTerminalPwd
     */
    public static function FIRST(): ConsTerminalPwd
    {
        return new self(self::FIRST);
    }

    /**
     * @return ConsTerminalPwd
     */
    public static function SECOND(): ConsTerminalPwd
    {
        return new self(self::SECOND);
    }

    /**
     * @return ConsTerminalPwd
     */
    public static function THREAD(): ConsTerminalPwd
    {
        return new self(self::THREAD);
    }
}
