<?php

declare(strict_types=1);

namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 *
 */
class ConstGuards extends Enum
{
    private const CLIENTS_WEB = 'clients_web';
    private const CLIENTS_API = 'clients_api';
    private const BEFORE_CLIENTS_WEB = 'before_clients_web';
    private const DRIVERS_WEB = 'drivers_web';
    private const DRIVERS_API = 'drivers_api';
    private const SYSTEM_WORKERS_WEB = 'system_workers_web';
    private const SYSTEM_WORKERS_API = 'system_workers_api';
    private const ADMIN_SUPER_WEB = 'admin_super_web';
    private const ADMIN_SUPER_API = 'admin_super_api';
    private const ADMIN_CORPORATE_WEB = 'admin_corporate_web';
    private const ADMIN_CORPORATE_API = 'admin_corporate_api';
    private const API_TERMINALS = 'api_terminals';

    /**
     * @return ConstGuards
     */
    public static function BEFORE_CLIENTS_WEB(): ConstGuards
    {
        return new self(self::BEFORE_CLIENTS_WEB);
    }

    /**
     * @return ConstGuards
     */
    public static function CLIENTS_WEB(): ConstGuards
    {
        return new self(self::CLIENTS_WEB);
    }

    /**
     * @return ConstGuards
     */
    public static function CLIENTS_API(): ConstGuards
    {
        return new self(self::CLIENTS_API);
    }

    /**
     * @return ConstGuards
     */
    public static function DRIVERS_WEB(): ConstGuards
    {
        return new self(self::DRIVERS_WEB);
    }

    /**
     * @return ConstGuards
     */
    public static function DRIVERS_API(): ConstGuards
    {
        return new self(self::DRIVERS_API);
    }

    /**
     * @return ConstGuards
     */
    public static function SYSTEM_WORKERS_WEB(): ConstGuards
    {
        return new self(self::SYSTEM_WORKERS_WEB);
    }

    /**
     * @return ConstGuards
     */
    public static function SYSTEM_WORKERS_API(): ConstGuards
    {
        return new self(self::SYSTEM_WORKERS_API);
    }

    /**
     * @return ConstGuards
     */
    public static function ADMIN_SUPER_WEB(): ConstGuards
    {
        return new self(self::ADMIN_SUPER_WEB);
    }

    /**
     * @return ConstGuards
     */
    public static function ADMIN_SUPER_API(): ConstGuards
    {
        return new self(self::ADMIN_SUPER_API);
    }

    /**
     * @return ConstGuards
     */
    public static function ADMIN_CORPORATE_WEB(): ConstGuards
    {
        return new self(self::ADMIN_CORPORATE_WEB);
    }

    /**
     * @return ConstGuards
     */
    public static function ADMIN_CORPORATE_API(): ConstGuards
    {
        return new self(self::ADMIN_CORPORATE_API);
    }

    /**
     * @return ConstGuards
     */
    public static function API_TERMINALS(): ConstGuards
    {
        return new self(self::API_TERMINALS);
    }
}
