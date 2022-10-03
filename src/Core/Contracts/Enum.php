<?php

declare(strict_types=1);

namespace Src\Core\Contracts;


use BadMethodCallException;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

use function array_key_exists;
use function array_keys;
use function array_search;
use function get_class;
use function in_array;

/**
 * Class Enum
 * @package Src\Core
 */
abstract class Enum implements JsonSerializable
{
    /**
     * @var array
     */
    protected static array $cache = [];
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Enum constructor.
     * @param $value
     * @throws ReflectionException
     */
    public function __construct($value)
    {
        if ($value instanceof static) {
            $value = $value->getValue();
        }

        if (!self::isValid($value)) {
            throw new UnexpectedValueException("Value '$value' is not part of the enum ".static::class);
        }

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid($value): bool
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function toArray(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            $reflection = new ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function keys(): array
    {
        return array_keys(static::toArray());
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function values(): array
    {
        $values = [];

        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * @param $key
     * @return bool
     * @throws ReflectionException
     */
    public static function isValidKey($key): bool
    {
        $array = static::toArray();

        return isset($array[$key]) || array_key_exists($key, $array);
    }

    /**
     * @param $name
     * @param $arguments
     * @return static
     * @throws ReflectionException
     */
    public static function __callStatic($name, $arguments): Enum
    {
        $array = static::toArray();

        if (isset($array[$name]) || array_key_exists($name, $array)) {
            return new static($array[$name]);
        }

        throw new BadMethodCallException("No static method or enum constant '$name' in class ".static::class);
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        $constants = new ReflectionClass(static::class);

        return $constants->getConstants();
    }

    /**
     * @return false|int|string
     * @throws ReflectionException
     */
    public function getKey()
    {
        return static::search($this->value);
    }

    /**
     * @param $value
     * @return false|int|string
     * @throws ReflectionException
     */
    public static function search($value)
    {
        return array_search($value, static::toArray(), true);
    }

    /**
     * @psalm-pure
     * @psalm-suppress InvalidCast
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param  null  $variable
     * @return bool
     */
    final public function equal($variable = null): bool
    {
        if ($variable instanceof self) {
            return $this->equals($variable);
        }

        return $this->getValue() === $variable;
    }

    /**
     * @param  null  $variable
     * @return bool
     */
    final public function equals($variable = null): bool
    {
        return $variable instanceof self
            && $this->getValue() === $variable->getValue()
            && static::class === get_class($variable);
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
