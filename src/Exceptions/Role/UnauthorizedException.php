<?php
declare(strict_types=1);

namespace Src\Exceptions\Role;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UnauthorizedException
 * @package Src\Exceptions\WorkerRole
 */
class UnauthorizedException extends HttpException
{
    /**
     * @var array
     */
    private $requiredRoles = [];

    /**
     * @var array
     */
    private $requiredPermissions = [];

    /**
     * @param  array  $roles
     * @return UnauthorizedException
     */
    public static function forRoles(array $roles): self
    {
        $message = 'User does not have the right roles.';

        if (config('permission.display_permission_in_exception')) {
            $permStr = implode(', ', $roles);
            $message = 'User does not have the right roles. Necessary roles are '.$permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredRoles = $roles;

        return $exception;
    }

    /**
     * @param  array  $permissions
     * @return UnauthorizedException
     */
    public static function forPermissions(array $permissions): self
    {
        $message = 'User does not have the right permissions.';

        if (config('permission.display_permission_in_exception')) {
            $permStr = implode(', ', $permissions);
            $message = 'User does not have the right permissions. Necessary permissions are '.$permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $permissions;

        return $exception;
    }

    /**
     * @param  array  $rolesOrPermissions
     * @return UnauthorizedException
     */
    public static function forRolesOrPermissions(array $rolesOrPermissions): self
    {
        $message = 'User does not have any of the necessary access rights.';

        if (config('permission.display_permission_in_exception') && config('permission.display_role_in_exception')) {
            $permStr = implode(', ', $rolesOrPermissions);
            $message = 'User does not have the right permissions. Necessary permissions are '.$permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $rolesOrPermissions;

        return $exception;
    }

    /**
     * @return UnauthorizedException
     */
    public static function notLoggedIn(): self
    {
        return new static(403, 'User is not logged in.', null, []);
    }

    /**
     * @return array
     */
    public function getRequiredRoles(): array
    {
        return $this->requiredRoles;
    }

    /**
     * @return array
     */
    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }
}
