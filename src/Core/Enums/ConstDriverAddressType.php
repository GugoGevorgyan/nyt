<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstDriverAddressType
 * @package Src\Core\Enums
 */
class ConstDriverAddressType extends Enum
{
    private const HOME = 'HOME';
    private const WORK = 'WORK';

    /**
     * @return ConstDriverAddressType
     */
    public static function HOME(): ConstDriverAddressType
    {
        return new self(self::HOME);
    }

    /**
     * @return ConstUtil
     */
    public static function WORK(): ConstDriverAddressType
    {
        return new self(self::WORK);
    }
}
