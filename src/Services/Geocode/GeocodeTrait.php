<?php

declare(strict_types=1);


namespace Src\Services\Geocode;


use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Src\Core\Enums\ConstRegionCity;

/**
 * Trait GeocodeTrait
 * @package Src\Services\Geocode
 */
trait GeocodeTrait
{
    /**
     * @param  array  $road
     * @param  string  $unit
     * @return array
     */
    #[ArrayShape([
        'distance' => 'float|int|mixed',
        'duration' => 'float|int|mixed',
        'waiting_duration' => 'float|int|mixed',
        'points' => 'array'
    ])]
    protected function singleRoadCalculation(array $road, string $unit = 'km/m'): array
    {
        $steps = $road['route']['legs'][0]['steps'];

        $duration = 0;
        $length = 0;
        $waiting_duration = 0;
        $points = [];

        foreach ($steps as $step) {
            $duration += $step['duration'];
            $length += $step['length'];
            $waiting_duration += $step['waiting_duration'];
            $points[] = $step['polyline']['points'];
        }

        return [
            'distance' => 'm/s' === $unit ? $length : round_d($length),
            'duration' => 'm/s' === $unit ? $duration : round_t($duration),
            'waiting_duration' => 'm/s' === $unit ? $waiting_duration : round_t($waiting_duration),
            'points' => self::coordinateFormat(array_flatten_adjustable($points)),
        ];
    }

    /**
     * @param  array  $road
     * @param  string  $unit
     * @return array
     */
    protected function multiplyRoadCalculation(array $road, string $unit = 'km/m'): array
    {
        $steps = $road['route']['legs'];

        $routes[] = ['distance' => 0.0, 'waiting_duration' => 0, 'duration' => 0, 'points' => []];

        foreach ($steps as $leg_key => $single_step) {
            foreach ($single_step['steps'] as $step) {
                $routes[$leg_key]['distance'] += $step['length'];
                $routes[$leg_key]['duration'] += $step['duration'];
                $routes[$leg_key]['waiting_duration'] += $step['waiting_duration'];
                $routes[$leg_key]['points'][] = $step['polyline']['points'];
            }
        }

        $routes[$leg_key]['distance'] = 'm/s' === $unit ? $routes[$leg_key]['distance'] : round_d($routes[$leg_key]['distance']);
        $routes[$leg_key]['duration'] = 'm/s' === $unit ? $routes[$leg_key]['duration'] : round_t($routes[$leg_key]['duration']);
        $routes[$leg_key]['waiting_duration'] = 'm/s' === $unit ? $routes[$leg_key]['waiting_duration'] : round_t($routes[$leg_key]['waiting_duration']);
        $routes[$leg_key]['points'] = self::coordinateFormat(array_flatten_adjustable($routes[$leg_key]['points']));

        return $routes;
    }

    /**
     * @param $address_components
     * @param $locality
     * @param $province
     * @param $code
     */
    protected function filterGeocodeAddressComponent($address_components, &$locality, &$province, &$code): void
    {
        $address_component = $address_components;

        array_walk(
            $address_component['Components'],
            static function ($item) use ($address_component, &$locality, &$province, &$code) {
                if ('locality' === $item['kind']) {
                    $locality = $item['name'];
                }

                if ('province' === $item['kind']) {
                    $province = $item['name'];
                }

                $code = $address_component['country_code'];
            }
        );
    }

    /**
     * @param $moscow_only_region
     * @param $province
     * @param $locality
     * @throws ReflectionException
     */
    protected function customRegionsPreserve($moscow_only_region, &$province, &$locality): void
    {
        if ($moscow_only_region && ConstRegionCity::MOSCOW()->equal($province)) {
            if ((!$locality || !ConstRegionCity::MOSCOW()->equal($locality))) {
                $locality = '';
                $province = ConstRegionCity::MOSCOW_REGION()->getValue();
            }
        } elseif ($moscow_only_region && ConstRegionCity::MOSCOW_REGION()->equal($province)) {
            $locality = '';
        }
    }
}
