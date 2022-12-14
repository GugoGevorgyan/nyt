<?php

declare(strict_types=1);

namespace Src\Core\Geo;

use function strlen;

/**
 * Class GeoHash
 * @package Src\Core
 */
class GeoHash
{
    private static string $table = '0123456789bcdefghjkmnpqrstuvwxyz';
    private static array $bits = [0b10000, 0b01000, 0b00100, 0b00010, 0b00001];

    /**
     * @param $hash
     * @param  float  $prec
     * @return string[]
     */
    public static function expand($hash, $prec = 0.00001): array
    {
        [$minlng, $maxlng, $minlat, $maxlat] = self::decode($hash);
        $dlng = ($maxlng - $minlng) / 2;
        $dlat = ($maxlat - $minlat) / 2;

        return [
            self::encode($minlng - $dlng, $maxlat + $dlat, $prec),
            self::encode($minlng + $dlng, $maxlat + $dlat, $prec),
            self::encode($maxlng + $dlng, $maxlat + $dlat, $prec),
            self::encode($minlng - $dlng, $maxlat - $dlat, $prec),
            self::encode($maxlng + $dlng, $maxlat - $dlat, $prec),
            self::encode($minlng - $dlng, $minlat - $dlat, $prec),
            self::encode($minlng + $dlng, $minlat - $dlat, $prec),
            self::encode($maxlng + $dlng, $minlat - $dlat, $prec),
        ];
    }

    /**
     * @param $hash
     * @return array
     */
    public static function decode($hash): array
    {
        $minlng = -180;
        $maxlng = 180;
        $minlat = -90;
        $maxlat = 90;

        for ($i = 0, $c = strlen($hash); $i < $c; $i++) {
            $v = strpos(self::$table, $hash[$i]);
            if (1 & $i) {
                if (16 & $v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (8 & $v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (4 & $v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (2 & $v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (1 & $v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
            } else {
                if (16 & $v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (8 & $v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (4 & $v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (2 & $v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (1 & $v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
            }
        }

        return [$minlng, $maxlng, $minlat, $maxlat];
    }

    /**
     * @param $lng
     * @param $lat
     * @param  float  $prec
     * @return string
     */
    public static function encode($lng, $lat, $prec = 0.00001): string
    {
        $minlng = -180;
        $maxlng = 180;
        $minlat = -90;
        $maxlat = 90;

        $hash = [];
        $error = 180;
        $isEven = true;
        $chr = 0b00000;
        $b = 0;

        while ($error >= $prec) {
            if ($isEven) {
                $next = ($minlng + $maxlng) / 2;
                if ($lng > $next) {
                    $chr |= self::$bits[$b];
                    $minlng = $next;
                } else {
                    $maxlng = $next;
                }
            } else {
                $next = ($minlat + $maxlat) / 2;
                if ($lat > $next) {
                    $chr |= self::$bits[$b];
                    $minlat = $next;
                } else {
                    $maxlat = $next;
                }
            }
            $isEven = !$isEven;

            if ($b < 4) {
                $b++;
            } else {
                $hash[] = self::$table[$chr];
                $error = max($maxlng - $minlng, $maxlat - $minlat);
                $b = 0;
                $chr = 0b00000;
            }
        }

        return implode('', $hash);
    }

    /**
     * @param $hash
     * @return array|array[]|float[]|int[]
     */
    public static function getRect($hash): array
    {
        [$minlng, $maxlng, $minlat, $maxlat] = self::decode($hash);

        return [
            [$minlng, $minlat],
            [$minlng, $maxlat],
            [$maxlng, $maxlat],
            [$maxlng, $minlat],
        ];
    }
}
