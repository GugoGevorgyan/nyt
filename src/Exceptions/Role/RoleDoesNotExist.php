<?php
declare(strict_types=1);

namespace Src\Exceptions\Role;

use InvalidArgumentException;

/**
 * Class RoleDoesNotExist
 * @package Src\Exceptions\WorkerRole
 */
class RoleDoesNotExist extends InvalidArgumentException
{
    /**
     * @param  string  $roleName
     * @return RoleDoesNotExist
     */
    public static function named(string $roleName)
    {
        return new static("There is no role named `{$roleName}`.");
    }

    /**
     * @param  int  $roleId
     * @return RoleDoesNotExist
     */
    public static function withId(int $roleId)
    {
        return new static("There is no role with id `{$roleId}`.");
    }
}
