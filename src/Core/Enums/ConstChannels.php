<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstChannels
 * @package Src\Core\Enums
 */
class ConstChannels extends Enum
{
    private const DRIVER_API = 'driver-api';
    private const CLIENT_API = 'client-api';
    private const CLIENT_WEB = 'client-web';
    private const BEFORE_CLIENT_WEB = 'before-client-web';
    private const WORKER_WEB = 'worker-web';
    private const WORKER_API = 'worker-api';
    private const PERSONAL_API = 'personal-api';
    private const ADMIN_CORPORATE_API = 'admin-corporate-api';
    private const ADMIN_CORPORATE_WEB = 'admin-corporate-web';

    /**
     * @return ConstChannels
     */
    public static function driver_api(): ConstChannels
    {
        return new self(self::DRIVER_API);
    }

    /**
     * @return ConstChannels
     */
    public static function client_api(): ConstChannels
    {
        return new self(self::CLIENT_API);
    }

    /**
     * @return ConstChannels
     */
    public static function client_web(): ConstChannels
    {
        return new self(self::CLIENT_WEB);
    }

    /**
     * @return ConstChannels
     */
    public static function before_client_web(): ConstChannels
    {
        return new self(self::BEFORE_CLIENT_WEB);
    }

    /**
     * @return ConstChannels
     */
    public static function worker_web(): ConstChannels
    {
        return new self(self::WORKER_WEB);
    }

    /**
     * @return ConstChannels
     */
    public static function worker_api(): ConstChannels
    {
        return new self(self::WORKER_API);
    }

    /**
     * @return ConstChannels
     */
    public static function personal_api(): ConstChannels
    {
        return new self(self::PERSONAL_API);
    }

    /**
     * @return ConstChannels
     */
    public static function admin_corporate_api(): ConstChannels
    {
        return new self(self::ADMIN_CORPORATE_API);
    }

    /**
     * @return ConstChannels
     */
    public static function admin_corporate_web(): ConstChannels
    {
        return new self(self::ADMIN_CORPORATE_WEB);
    }
}
