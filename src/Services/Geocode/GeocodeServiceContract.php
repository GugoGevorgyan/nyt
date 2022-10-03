<?php

declare(strict_types=1);


namespace Src\Services\Geocode;

use Illuminate\Support\Collection;

/**
 * Interface GeocodeServiceContract
 * @package Src\Services\Geocode
 */
interface GeocodeServiceContract
{
    /**
     * @param  array  $coordinates  [0][1]['lat']['lut']
     * @param  bool  $moscow_only_region  do not take into account the cities of the Moscow region
     * @param  bool  $in_session
     * @return null|array{locality: string, province:string, country_code:string, address:string, short_address:string}
     */
    public function getRightGeocode(array $coordinates, bool $moscow_only_region = false, bool $in_session = false): ?array;

    /**
     * @param  float  $lat
     * @param  float  $lut
     * @param  string|null  $precision
     * @param  bool  $in_session
     * @return mixed
     */
    public function fullAddressGeocode(float $lat, float $lut, string $precision = null, bool $in_session = false): ?array;

    /**
     * @param  bool  $in_behind_from
     * @param  bool  $in_behind_to
     * @param $behind_calculation
     * @param  array  $from_cord
     * @param  array  $to_cord
     * @param  bool  $rectilinear
     * @return array
     */
    public function behindDistanceAssociated(
        bool $in_behind_from,
        bool $in_behind_to,
        $behind_calculation,
        array $from_cord,
        array $to_cord,
        bool $rectilinear = false
    ): array;

    /**
     * @param $road_coordinates
     * @param $polygon
     * @return array
     */
    public function behindIntersectionCoordinates($road_coordinates, $polygon): array;

    /**
     * @param  array  $coordinate  = ['lat']['lut']
     * @param  array  $behind_polygon
     * @return float
     */
    public function behindIntersectionCoordinateDistance(array $coordinate, array $behind_polygon): float;

    /**
     * @param  array  $coordinate
     * @param  array  $behind_polygon
     * @return array{distance: float|string, cord: array}
     */
    public function behindIntersectionCoordinateCord(array $coordinate, array $behind_polygon): array;

    /**
     * @param $lat
     * @param $lut
     * @return bool
     */
    public function detectInMkad($lat, $lut): bool;

    /**
     * @param $area
     * @param  array  $point
     * @param  int  $precision
     * @return bool
     */
    public function pointInPolygon($area, array $point, int $precision = 16): bool;

    /**
     * @param  array  $from_cord
     * @param  array  $to_cord
     * @param  int|null  $micro_time
     * @param  bool  $first
     * @param  string  $unit  km = km/m, m = m/s
     * @return array{distance: float, duration: float, waiting_duration: float, points: array}
     */
    public function roadCalculation(array $from_cord, array $to_cord, int $micro_time = null, bool $first = true, string $unit = 'km/m'): ?array;

    /**
     * @param  array  $tariff_behind_mkad
     * @param $from_cord
     * @return bool
     */
    public function behindDistanceToMKAD(array $tariff_behind_mkad, $from_cord): bool;

    /**
     * @return mixed
     */
    public function getMkadCoordinates();

    /**
     * @param  array  $a_cord
     * @param  array  $b_cord
     * @param  bool  $distance_precision
     * @param  bool  $duration_precision
     * @return array
     */
    public function getDistanceCoordinates(array $a_cord, array $b_cord, bool $distance_precision = true, bool $duration_precision = false): ?Collection;

    /**
     * @param  array  $a_cord
     * @param  array  $b_cord
     * @param  array  $polygon
     * @param  bool  $distance_precision
     * @param  bool  $duration_precision
     * @return array
     */
    public function getRoadIntersectABC(array $a_cord, array $b_cord, array $polygon, bool $distance_precision = true, bool $duration_precision = false): array;

    /**
     * @param  array  $coordinate
     * @param  array  $road
     * @return array
     */
    public function calculateDistanceFromRoad(array $coordinate, array $road): array;

    /**
     * @return array
     */
    public function getYKeys(): array;

    /**
     * @param $address
     * @return array|null
     */
    public function getCordsByAddress($address): ?array;

    /**
     * @param $lat
     * @param $lut
     * @return string
     */
    public function getAddressByCords($lat, $lut): string;
}
