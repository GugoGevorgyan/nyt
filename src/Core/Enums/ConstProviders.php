<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstProviders
 * @package Src\Core\Enums
 */
class ConstProviders extends Enum
{
    private const CLIENTS = 'clients';
    private const DRIVERS = 'drivers';
    private const BEFORE_CLIENTS = 'before_clients';
    private const ADMIN_SUPER = 'admin_super';
    private const SYSTEM_WORKERS = 'system_workers';
    private const ADMIN_CORPORATE = 'admin_corporate';
    private const API_CLIENT = 'api_client';

    /**
     * @return ConstProviders
     */
    public static function CLIENTS(): ConstProviders
    {
        return new self(self::CLIENTS);
    }

    /**
     * @return ConstProviders
     */
    public static function DRIVERS(): ConstProviders
    {
        return new self(self::DRIVERS);
    }

    /**
     * @return ConstProviders
     */
    public static function BEFORE_CLIENTS(): ConstProviders
    {
        return new self(self::BEFORE_CLIENTS);
    }

    /**
     * @return ConstProviders
     */
    public static function ADMIN_SUPER(): ConstProviders
    {
        return new self(self::ADMIN_SUPER);
    }

    /**
     * @return ConstProviders
     */
    public static function SYSTEM_WORKERS(): ConstProviders
    {
        return new self(self::SYSTEM_WORKERS);
    }

    /**
     * @return ConstProviders
     */
    public static function ADMIN_CORPORATE(): ConstProviders
    {
        return new self(self::ADMIN_CORPORATE);
    }
}
