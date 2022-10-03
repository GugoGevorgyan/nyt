<?php

declare(strict_types=1);

namespace Src\Core\Additional;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;

use function get_class;
use function is_object;

/**
 * Class Guard
 * @package Src\Roles
 */
class Guard
{
    /**
     * @param $class
     * @return string
     * @throws ReflectionException
     */
    public static function getDefaultName($class): string
    {
        $default = config('auth.defaults.guard');

        return static::getNames($class)->first() ?: (string)$default;
    }

    /**
     * @param $model
     * @return Collection
     * @throws ReflectionException
     */
    public static function getNames($model): Collection
    {
        if (is_object($model)) {
            $guard_name = $model->guard_name ?? null;
        }

        if (!isset($guard_name)) {
            $class = is_object($model) ? get_class($model) : $model;

            $guard_name = (new ReflectionClass($class))->getDefaultProperties()['guard_name'] ?? null;
        }

        if ($guard_name) {
            return collect($guard_name);
        }

        return collect(config('auth.guards'))
            ->map(
                static function ($guard) {
                    if (!isset($guard['provider'])) {
                        return;
                    }

                    return config("auth.providers.{$guard['provider']}.model");
                }
            )
            ->filter(fn($model) => $class === $model)
            ->keys();
    }
}
