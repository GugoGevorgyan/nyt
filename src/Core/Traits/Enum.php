<?php

declare(strict_types=1);


namespace Src\Core\Traits;


use Exception;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

use function array_key_exists;

/**
 * Trait Enum
 * @package Src\Support
 */
trait Enum
{
    /**
     * @var array
     */
    private static array $values = [];
    /**
     * @var array
     */
    private static array $valueMap = [];

    /**
     * Enum constructor.
     * @param $value
     */
    public function __construct(private $value)
    {
    }

    /**
     * @return Enum[]
     * @throws Exception
     */
    public static function getValues(): array
    {
        $className = static::class;
        if (!array_key_exists($className, self::$values)) {
            throw new RuntimeException(sprintf('Enum is not initialized, enum=%s', $className));
        }
        return self::$values[$className];
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function getEnumObject($value): mixed
    {
        if (empty($value)) {
            return null;
        }
        $className = static::class;

        return self::$valueMap[$className][$value];
    }

    /**
     * @throws ReflectionException
     */
    public static function init(): void
    {
        $className = static::class;
        $class = new ReflectionClass($className);

        if (array_key_exists($className, self::$values)) {
            throw new RuntimeException(sprintf('Enum has been already initialized, enum=%s', $className));
        }

        self::$values[$className] = [];
        self::$valueMap[$className] = [];

        /** @var Enum[] $enumFields */
        $enumFields = array_filter($class->getStaticProperties(), fn($property) => $property instanceof Enum);

        if (0 == count($enumFields)) {
            throw new RuntimeException(sprintf('Enum has not values, enum=%s', $className));
        }

        foreach ($enumFields as $property) {
            if (array_key_exists($property->getValue(), self::$valueMap[$className])) {
                throw new RuntimeException(sprintf('Duplicate enum value %s from enum %s', $property->getValue(), $className));
            }

            self::$values[$className][] = $property;
            self::$valueMap[$className][$property->getValue()] = $property;
        }
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->value;
    }
}
