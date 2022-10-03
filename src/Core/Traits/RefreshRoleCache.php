<?php

declare(strict_types=1);

namespace Src\Core\Traits;

use Src\Core\Additional\RoleRegister;

/**
 * Trait RefreshRoleCache
 * @package Src\Traits
 */
trait RefreshRoleCache
{
    /**
     *
     */
    public static function bootRefreshesPermissionCache(): void
    {
        static::saved(static fn() => app(RoleRegister::class)->forgetCachedPermissions());

        static::deleted(static fn() => app(RoleRegister::class)->forgetCachedPermissions());
    }
}
