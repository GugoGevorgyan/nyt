<?php
declare(strict_types=1);

namespace Src\Exceptions\Role;

use InvalidArgumentException;

/**
 * Class RoleAlreadyExists
 * @package Src\Exceptions\WorkerRole
 */
class RoleAlreadyExists extends InvalidArgumentException
{
    /**
     * @param  string  $roleName
     * @param  string  $guardName
     * @return RoleAlreadyExists
     */
    public static function create(string $roleName, string $guardName)
    {
        return new static("A role `{$roleName}` already exists for guard `{$guardName}`.");
    }
}
