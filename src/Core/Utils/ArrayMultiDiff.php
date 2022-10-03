<?php

declare(strict_types=1);

namespace Src\Core\Utils;

/**
 *
 */
class ArrayMultiDiff
{
    /**
     * @param  array  $array1
     * @param  array  $array2
     * @return array
     */
    public static function strictComparison(array $array1, array $array2): array
    {
        return static::compare($array1, $array2);
    }

    /**
     * @param  array  $array1
     * @param  array  $array2
     * @param  bool  $strict
     * @return array
     */
    public static function compare(array $array1, array $array2, bool $strict = true): array
    {
        if (!\is_array($array1)) {
            throw new \InvalidArgumentException('$array1 must be an array!');
        }

        if (!\is_array($array2)) {
            return $array1;
        }

        $result = [];

        foreach ($array1 as $key => $value) {
            if (!\array_key_exists($key, $array2)) {
                $result[$key] = $value;
                continue;
            }

            if (\is_array($value) && count($value) > 0) {
                $recursive_array_diff = static::compare($value, $array2[$key], $strict);

                if (count($recursive_array_diff) > 0) {
                    $result[$key] = $recursive_array_diff;
                }

                continue;
            }

            $value1 = $value;
            $value2 = $array2[$key];

            if ($strict ? \is_float($value1) && \is_float($value2) : \is_float($value1) || \is_float($value2)) {
                $value1 = (string)$value1;
                $value2 = (string)$value2;
            }

            if ($strict ? $value1 !== $value2 : $value1 != $value2) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param $array1
     * @param $array2
     * @return array
     */
    public static function looseComparison($array1, $array2): array
    {
        return static::compare($array1, $array2, false);
    }
}
