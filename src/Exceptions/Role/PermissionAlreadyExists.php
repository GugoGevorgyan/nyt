<?php
declare(strict_types=1);

namespace Src\Exceptions\Role;

use InvalidArgumentException;

/**
 * Class PermissionAlreadyExists
 * @package Src\Exceptions\WorkerRole
 */
class PermissionAlreadyExists extends InvalidArgumentException
{
    /**
     * @param  string  $permissionName
     * @param  string  $guardName
     * @return PermissionAlreadyExists
     */
    public static function create(string $permissionName, string $guardName)
    {
        return new static("A `{$permissionName}` permission already exists for guard `{$guardName}`.");
    }
}
