<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

class ConstTariffRound extends Enum
{
    public const NOT_ROUND = ['type' => 1, 'text' => 'Не округлять'];
    public const ROUND_DOWN = ['type' => 2, 'text' => 'Округлить вниз'];
    public const ROUND_UP = ['type' => 3, 'text' => 'Округлить вверх'];

    /**
     * @return ConstTariffRound
     */
    public static function NOT_ROUND(): ConstTariffRound
    {
        return new self(self::NOT_ROUND);
    }

    /**
     * @return ConstTariffRound
     */
    public static function ROUND_DOWN(): ConstTariffRound
    {
        return new self(self::ROUND_DOWN);
    }

    /**
     * @return ConstTariffRound
     */
    public static function ROUND_UP(): ConstTariffRound
    {
        return new self(self::ROUND_UP);
    }
}
