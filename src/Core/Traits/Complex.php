<?php

declare(strict_types=1);


namespace Src\Core\Traits;


use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use ReflectionClass;
use ReflectionException;

use function func_get_args;

/**
 * Class Complex
 * @package Src\Core
 * @method handle()
 */
trait Complex
{
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @return mixed
     * @throws ReflectionException
     */
    public static function complex(): mixed
    {
        $complex_instance = new static(...func_get_args());
        $params = (new ReflectionClass($complex_instance))->getMethod('handle')->getParameters();

        return static::autoInject($complex_instance, $params);
    }

    /**
     * @param $complex_instance
     * @param $params
     * @return mixed
     */
    protected static function autoInject($complex_instance, $params): mixed
    {
        if ($params) {
            foreach ($params as $param) {
                $complex_instance->{$param->getName()} = resolve($param->getType()->getName());
            }
        }

        return dispatch_now($complex_instance);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws ReflectionException
     */
    public static function __callStatic($name, $arguments)
    {
        $complex_instance = new static(...$arguments);
        $params = (new ReflectionClass($complex_instance))->getMethod('handle')->getParameters();

        return static::autoInject($complex_instance, $params);
    }

    /**
     * @param  string  $connection
     * @return Connection
     */
    protected function redis(string $connection = 'app'): Connection
    {
        return Redis::connection($connection);
    }

    /**
     * @param  mixed  ...$injects
     */
    protected function inject(...$injects): void
    {
        if (!$injects) {
            return;
        }

        foreach ($injects as $inject) {
            $injected = app($inject);
            $injector = substr($inject, strrpos($inject, '\\') + 1);
            if (strpos($injector, 'Service')) {
                $injector = lcfirst(str_replace('Contract', '', $injector));
                $this->{$injector} = $injected;
            }
            if (!strpos($injector, 'Service')) {
                $injector = lcfirst($injector);
                $this->{$injector} = $injected;
            }
        }
    }
}
