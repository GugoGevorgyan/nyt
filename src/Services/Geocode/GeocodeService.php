<?php

declare(strict_types=1);


namespace Src\Services\Geocode;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Polygon\Polygon;
use ReflectionException;
use ServiceEntity\BaseService;
use Spatie\Async\Pool;
use Src\Core\Complex\GetRightYKey;
use Src\Core\Enums\ConstApiKey;
use Src\Models\Location\Area;
use Src\Repositories\Address\AddressContract;
use Src\Repositories\ApiKey\ApiKeyContract;
use Src\Repositories\Area\AreaContract;
use Src\Support\Facades\Geo;

use function count;
use function is_array;
use function is_string;
use function strlen;

/**
 * Class GeocodeService
 * @package Src\Services\Geocode
 */
final class GeocodeService extends BaseService implements GeocodeServiceContract
{
    use GeocodeTrait;

    /**
     * GeocodeService constructor.
     * @param  AreaContract  $areaContract
     * @param  AddressContract  $addressContract
     * @param  ApiKeyContract  $apiKeyContract
     */
    public function __construct(protected AreaContract $areaContract, protected AddressContract $addressContract, protected ApiKeyContract $apiKeyContract)
    {
    }

    /**
     * @param $coordinates
     * @return array
     */
    protected static function coordinateFormat($coordinates): array
    {
        $cords = [];

        foreach ($coordinates as $key => $coordinate) {
            if (is_string($key)) {
                $cords = $coordinates;
                break;
            }

            $cords[] = ['lat' => $coordinate[0], 'lut' => $coordinate[1]];
        }

        return $cords;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function getRightGeocode(array $coordinates, bool $moscow_only_region = false, bool $in_session = false): ?array
    {
        $coordinates = isset($coordinates['lut']) ? [$coordinates['lat'], $coordinates['lut']] : [$coordinates[0], $coordinates[1]];

        $geocode = $this->existsAddress($coordinates);

        $locality = '';
        $province = '';
        $country_code = '';
        $address = '';
        $short_address = '';

        if ($geocode) {
            if ($geocode->lat === (float)$coordinates[0] && $geocode->lut === (float)$coordinates[1]) {
                $locality = $geocode->locality;
                $province = $geocode->province;
                $country_code = $geocode->code;
                $address = $geocode->address;
                $short_address = $geocode->short_address;
            }

            $this->customRegionsPreserve($moscow_only_region, $province, $locality);
        } else {
            $iterate = 0;
            $cord = $coordinates[1].' '.$coordinates[0];
            $geocode = Geo::geocode($coordinates[1].','.$coordinates[0]);

            foreach ($geocode['GeoObjectCollection']['featureMember'] as $geo) {
                ++$iterate;

                $address_components = $geo['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address'];

                if ($geo['GeoObject']['Point']['pos'] === $cord) {
                    $this->filterGeocodeAddressComponent($address_components, $locality, $province, $country_code);
                    $short_address = $geo['GeoObject']['name'];
                    $address = $address_components['formatted'];
                }

                if ($iterate === count($geocode['GeoObjectCollection']['featureMember'])) {
                    $address_component = $geocode['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address'];
                    $this->filterGeocodeAddressComponent($address_component, $locality, $province, $country_code);
                    $this->customRegionsPreserve($moscow_only_region, $province, $locality);
                    $short_address = $geocode['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'];
                    $address = $address_component['formatted'];
                }
            }
        }

        $in_session
            ? session()->flash('locality', compact('locality', 'province', 'country_code', 'address', 'short_address'))
            : null;

        return compact('locality', 'province', 'country_code', 'address', 'short_address');
    }

    /**
     * @param $from_cord
     * @return object|null
     */
    protected function existsAddress($from_cord): ?object
    {
        $a_cord = !isset($from_cord['lat']) && isset($from_cord[0]) ? ['lat' => $from_cord[0], 'lut' => $from_cord[1]] : $from_cord;

        return $this->addressContract->where('lat', '=', $a_cord['lat'])->where('lut', '=', $a_cord['lut'])->findFirst() ?: null;
    }

    /**
     * @inheritDoc
     * @todo not ended
     */
    public function fullAddressGeocode(float $lat, float $lut, string $precision = null, bool $in_session = false): ?array
    {
        $geo = Geo::geocode($lut.','.$lat);

        if (!$geo) {
            return null;
        }

        $result = [];

        foreach ($geo['GeoObjectCollection']['featureMember'] as $items) {
            if (!$precision) {
                $result = $this->fullAddressDistributor($items);
                break;
            }

            if ('exact' === $precision && 'exact' === $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['precision']) {
                $result = $this->fullAddressDistributor($items);
                break;
            }

            if ('exact' === $precision && 'street' === $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['precision']) {
                break;
            }

            if ('street' === $precision && 'street' === $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['precision']) {
                $result = $this->fullAddressDistributor($items);
                break;
            }

            if ('street' === $precision && 'street' === $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['precision']) {
                break;
            }

            if ('other' === $precision && 'other' === $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['precision']) {
                $result = $this->fullAddressDistributor($items);
                break;
            }
        }

        $in_session
            ? session()->put('locality',
            [
                'precision' => $result['precision'],
                'full_address' => $result['full_address'],
                'short_address' => $result['short_address'],
                'country_code' => $result['country_code'],
                'country' => $result['country'],
                'postal_code' => $result['postal_code'],
            ])
            : null;

        return $result;
    }

    /**
     * @param $items
     * @return array
     */
    #[ArrayShape([
        'precision' => 'mixed',
        'full_address' => 'mixed',
        'short_address' => 'mixed',
        'country_code' => 'mixed',
        'country' => 'mixed',
        'postal_code' => 'mixed'
    ])] protected function fullAddressDistributor($items): array
    {
        $details = $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails'];
        $address = $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address'];

        return [
            'precision' => $items['GeoObject']['metaDataProperty']['GeocoderMetaData']['precision'],
            'full_address' => $address['formatted'],
            'short_address' => $items['GeoObject']['name'],
            'country_code' => $address['country_code'],
            'country' => $details['Country']['CountryName'] ?? '',
            'postal_code' => $address['postal_code'] ?? '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function behindDistanceAssociated(
        $in_behind_from,
        $in_behind_to,
        $behind_calculation,
        array $from_cord,
        array $to_cord,
        bool $rectilinear = false
    ): array {
        $behind_intersection = [];

        $behind_distance_in = [];
        $behind_distance_out = [];

        $behind_distance_in_rectilinear = 0.0;
        $behind_distance_out_rectilinear = 0.0;

        if ((!$in_behind_from && $in_behind_to) || ($in_behind_from && !$in_behind_to)) {
            $behind_intersection = $this->behindIntersectionCoordinates($behind_calculation['points'], $this->getMkadCoordinates());
        }

        if ($in_behind_from && !$in_behind_to) {
            $behind_distance_in = Geo::matrix($behind_intersection['lat'].','.$behind_intersection['lut'], $from_cord['lat'].','.$from_cord['lut']);
            $behind_distance_out = Geo::matrix($behind_intersection['lat'].','.$behind_intersection['lut'], $to_cord['lat'].','.$to_cord['lut']);

            if ($rectilinear) {
                $behind_distance_in_rectilinear = distance_cords(
                    $behind_intersection['lat'],
                    $behind_intersection['lut'],
                    $from_cord['lat'],
                    $from_cord['lut']
                );
                $behind_distance_out_rectilinear = distance_cords($behind_intersection['lat'], $behind_intersection['lut'], $to_cord['lat'], $to_cord['lut']);
            }
        }

        if (!$in_behind_from && $in_behind_to) {
            $behind_distance_in = Geo::matrix($behind_intersection['lat'].','.$behind_intersection['lut'], $from_cord['lat'].','.$from_cord['lut']);
            $behind_distance_out = Geo::matrix($behind_intersection['lat'].','.$behind_intersection['lut'], $to_cord['lat'].','.$to_cord['lut']);

            if ($rectilinear) {
                $behind_distance_in_rectilinear = distance_cords(
                    $behind_intersection['lat'],
                    $behind_intersection['lut'],
                    $from_cord['lat'],
                    $from_cord['lut']
                );
                $behind_distance_out_rectilinear = distance_cords($behind_intersection['lat'], $behind_intersection['lut'], $to_cord['lat'], $to_cord['lut']);
            }
        }

        return compact('behind_intersection', 'behind_distance_in', 'behind_distance_out', 'behind_distance_in_rectilinear', 'behind_distance_out_rectilinear');
    }

    /**
     * @inheritDoc
     */
    public function behindIntersectionCoordinates($road_coordinates, $polygon): array
    {
        $distances = [];

        foreach ($polygon as $mk_key => $mk) {
            foreach ($road_coordinates as $road_key => $road_coordinate) {
                $distance = distance_by_coordinates($mk[0], $mk[1], $road_coordinate['lat'], $road_coordinate['lat']);
                $distances[] = ['distances' => round($distance, 2), 'mkad_key' => $mk_key, 'road_key' => $road_key];
            }
        }

        $distance_value = [];

        foreach ($distances as $distance) {
            $distance_value[] = $distance['distances'];
        }

        $min_value = array_keys($distance_value, min($distance_value));
        return $road_coordinates[$distances[array_first($min_value)]['road_key']];
    }

    /**
     * @inheritDoc
     */
    public function getMkadCoordinates()
    {
        return $this->areaContract->findBy('name', Area::AREA_MKAD, ['name', 'area'])->area;
    }

    /**
     * @inheritDoc
     */
    public function behindIntersectionCoordinateDistance(array $coordinate, array $behind_polygon): float
    {
        $distances = [];

        $pool = Pool::create();
        foreach ($behind_polygon as $key => $polygon) {
            $pool
                ->add(fn() => $key)
                ->then(function ($key) use ($polygon, $coordinate, &$distances) {
                    $distance = distance_by_coordinates($polygon[0], $polygon[1], (float)$coordinate['lat'], (float)$coordinate['lut']);
                    $distances[] = compact('key', 'distance');
                });
        }
        $pool->wait();

        $distance_value = [];

        foreach ($distances as $distance) {
            $distance_value[] = $distance['distance'];
        }

        return min($distance_value);
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'distance' => 'mixed',
        'cord' => 'mixed'
    ])]
    public function behindIntersectionCoordinateCord(array $coordinate, array $behind_polygon): array
    {
        $distances = [];

        $pool = Pool::create();
        foreach ($behind_polygon as $key => $polygon) {
            $pool
                ->add(fn() => $key)
                ->then(function ($key) use ($polygon, $coordinate, &$distances) {
                    $distance = distance_by_coordinates($polygon[0], $polygon[1], (float)$coordinate['lat'], (float)$coordinate['lut']);
                    $distances[] = compact('key', 'distance', 'polygon');
                });
        }
        $pool->wait();

        $distance_value = [];

        foreach ($distances as $key => $distance) {
            $distance_value[$key] = $distance['distance'];
        }

        $key = array_keys($distance_value, min($distance_value));
        $data = $distances[$key[0]];

        return ['distance' => $data['distance'], 'cord' => $data['polygon']];
    }

    /**
     * @inheritDoc
     */
    public function detectInMkad($lat, $lut): bool
    {
        $polygon = new Polygon($this->getMkadCoordinates());
        $polygon->setPrecision(16);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function pointInPolygon($area, array $point, int $precision = 16): bool
    {
        if (is_string($area)) {
            $area = $this->areaContract->firstWhere(['name', '=', $area], ['name', 'area'])->area;
        }

        $polygon = new Polygon($area);
        $polygon->setPrecision($precision);

        return $polygon->pointInPolygon(new Coordinate([$point['lat'] ?? $point[0], $point['lut'] ?? $point[1]]));
    }

    /**
     * // @TODO NON TESTED. WORKED ONLY UNIX SYSTEM // -> INSTALL PHP_PCNTL && PHP_POSIX
     * @inheritDoc
     */
    public function behindDistanceToMKAD(array $tariff_behind, $from_cord): bool
    {
        $between = false;
        $pool = Pool::create();

        $mk_cords = $this->getMkadCoordinates();

        foreach ($mk_cords as $cord) {
            $pool->add(
                static function () use ($cord, $from_cord) {
                    return distance_cords($cord[0], $cord[1], $from_cord['lat'], $from_cord['lut']);
                }
            )->then(
                static function (float $in_behind_distance) use ($pool, $tariff_behind, &$between) {
                    $pool->add(
                        static function () use ($tariff_behind, $in_behind_distance, &$between) {
                            foreach ($tariff_behind as $behind_distance) {
                                if ((string)$in_behind_distance >= $behind_distance['initial_distance'] && (string)$in_behind_distance <= $behind_distance['final_distance']) {
                                    $between = true;
                                }
                            }
                        }
                    );
                }
            );
        }

        $pool->wait();

        return $between;
    }

    /**
     * @inheritDoc
     */
    public function getDistanceCoordinates(array $a_cord, array $b_cord, bool $distance_precision = true, bool $duration_precision = false): Collection
    {
        $a_cord = !isset($a_cord['lat']) && isset($a_cord[0]) ? ['lat' => $a_cord[0], 'lut' => $a_cord[1]] : $a_cord;
        $b_cord = !isset($b_cord['lat']) && isset($b_cord[0]) ? ['lat' => $b_cord[0], 'lut' => $b_cord[1]] : $b_cord;

        $exists_matrix = $this->existsMatrix($a_cord, $b_cord);

        if ($exists_matrix) {
            $geo['distance']['value'] = $exists_matrix['distance'];
            $geo['duration']['value'] = $exists_matrix['duration'];
        } else {
            $geo = Geo::matrix($a_cord['lat'].','.$a_cord['lut'], $b_cord['lat'].','.$b_cord['lut']);
        }

        $distance = round($geo['distance']['value'] / 1000, $distance_precision ? 2 : 0);
        $duration = round($geo['duration']['value'] / 60, $duration_precision ? 2 : 0);

        return $this->parseResult($data, ['distance', 'duration'], [$distance, $duration]);
    }

    /**
     * @param $from_cord
     * @param $to_cord
     * @return array|null
     */
    protected function existsMatrix($from_cord, $to_cord): ?array
    {
        $from = $this->addressContract->where('lat', $from_cord['lat'])->where('lut', $from_cord['lut'])->findFirst();
        $to = $this->addressContract->where('lat', $to_cord['lat'])->where('lut', $to_cord['lut'])->findFirst();

        if (!$from || !$to) :
            return null;
        endif;

        $from->load(['initial_route' => fn(HasMany $initial) => $initial->where('end_address_id', '=', $to->address_id)]);

        if (!$from->initial_route->count()) :
            return null;
        endif;

        return ['distance' => $from->initial_route->first()->distance, 'duration' => $from->initial_route->first()->duration];
    }

    /**
     * @inheritDoc
     * @noinspection DuplicatedCode
     */
    public function getRoadIntersectABC(array $a_cord, array $b_cord, array $polygon, bool $distance_precision = true, bool $duration_precision = false): array
    {
        $cords_a_b = $this->roadCalculation($a_cord, $b_cord); // @todo critic speed, fix
        $intersect = $this->behindIntersectionCoordinates($cords_a_b['points'], $polygon); // @todo critic speed, fix

        $exists_matrix_a_b = $this->existsMatrix($a_cord, ['lat' => $intersect['lat'], 'lut' => $intersect['lut']]);
        $exists_matrix_b_c = $this->existsMatrix($b_cord, ['lat' => $intersect['lat'], 'lut' => $intersect['lut']]);

        if ($exists_matrix_a_b) {
            $distance_a_b['distance']['value'] = $exists_matrix_a_b['distance'];
            $distance_a_b['duration']['value'] = $exists_matrix_a_b['duration'];
        } else {
            $distance_a_b = $this->roadCalculation($a_cord, $intersect, now()->timestamp);
        }

        if ($exists_matrix_b_c) {
            $distance_b_c['distance']['value'] = $exists_matrix_b_c['distance'];
            $distance_b_c['duration']['value'] = $exists_matrix_b_c['duration'];
        } else {
            $distance_b_c = $this->roadCalculation($b_cord, $intersect, now()->timestamp);
        }

        $a_b_distance = $distance_a_b['distance'];
        $a_b_duration = $distance_a_b['duration'];

        $b_c_distance = $distance_b_c['distance'];
        $b_c_duration = $distance_b_c['duration'];

        return compact('a_b_distance', 'a_b_duration', 'b_c_distance', 'b_c_duration');
    }

    /**
     * @inheritDoc
     */
    public function roadCalculation(array $from_cord, array $to_cord, int $micro_time = null, bool $first = true, string $unit = 'km/m'): ?array
    {
        ///////////////////////////////////////////////////ON EXISTS IN TABLE///////////////////////////////////////////////////
        $result = $this->existsRoute($from_cord, $to_cord);

        // exists in addresses data
        if ($result) {
            return [
                'distance' => 'm/s' === $unit ? (int)round($result['length'] * 1000) : $result['length'],
                'duration' => 'm/s' === $unit ? (int)round($result['duration'] * 60) : $result['duration'],
                'points' => $result['points'],
            ];
        }

        $road = Geo::route($from_cord['lat'].','.$from_cord['lut'], $to_cord['lat'].','.$to_cord['lut'], $micro_time);

        if ('OK' !== $road['route']['legs'][0]['status']) {
            return null;
        }

        if ($first) {
            ///////////////////////////////////////////////////MULTIPLY///////////////////////////////////////////////////
            return $this->singleRoadCalculation($road, $unit);
        }

        ////////////////////////////////////////////////////SINGLE///////////////////////////////////////////////////
        return $this->multiplyRoadCalculation($road, $unit);
    }

    /**
     * @param  array  $from_cord
     * @param  array  $to_cord
     * @return array|null
     */
    protected function existsRoute(array $from_cord, array $to_cord): ?array
    {
        $from = $this->addressContract->where('lat', $from_cord['lat'])->where('lut', '=', $from_cord['lut'])->findFirst();
        $to = $this->addressContract->where('lat', '=', $to_cord['lat'])->where('lut', '='.$to_cord['lut'])->findFirst();

        if (!$from || !$to) {
            return null;
        }

        $from->load(['initial_route' => fn(HasMany $initial) => $initial->where('end_address_id', '=', $to->address_id)]);

        if (!$from->initial_route->count()) {
            return null;
        }

        $from->load(
            [
                'initial_routes' => fn(HasManyThrough $initial_routes) => $initial_routes
                    ->where('detail_id', '=', $from->initial_route->first()->address_detail_id)
            ]
        );

        if (!$from->initial_routes) {
            return null;
        }

        $route = $from->initial_route->first();
        $routes = $from->initial_routes;

        return [
            'length' => $route->distance,
            'duration' => $route->duration,
            'points' => $routes->route
        ];
    }

    /**
     * @inheritDoc
     */
    public function calculateDistanceFromRoad(array $coordinate, array $road): array
    {
        $pool = Pool
            ::create()
            ->autoload(__DIR__.'/../../../app/AsyncLoader/RuntimeAutoload.php')
            ->concurrency(4)
            ->timeout(10);

        foreach ($road as $road_cord) {
            $pool->add(function () use ($coordinate, $road_cord) {
                return distance_cords($coordinate['lat'], $coordinate['lut'], $road_cord['lat'], $road_cord['lut'], 'm');
            })->then(function (int $output) use (&$distances) {
                $distances[] = $output;
            });
        }

        $pool->wait();

        return $distances ?? [];
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    #[ArrayShape([
        '
    geocode_key' => 'string',
        'matrix_key' => 'string',
        'route_key' => 'string'
    ])]
    public function getYKeys(): array
    {
        $geocode = GetRightYKey::complex();
        $matrices = $this->apiKeyContract->where('type', '=', ConstApiKey::Y_MATRIX()->getValue())->findFirst(['type', 'params', 'url']);
        $routes = $this->apiKeyContract->where('type', '=', ConstApiKey::Y_ROUTE()->getValue())->findFirst(['type', 'params', 'url']);

        foreach ($matrices->params as $matrix) {
            if (true === $matrix['used']) {
                $matrix_key = str_ireplace(['$[version]', '$[key]'], [$matrix['version'], $matrix['key']], $matrices->url);
                break;
            }
        }

        foreach ($routes->params as $route) {
            if (true === $route['used']) {
                $route_key = str_ireplace(['$[version]', '$[key]'], [$route['version'], $route['key']], $routes->url);
                break;
            }
        }

        $geo_key = rtrim(substr($geocode, strpos($geocode, 'apikey=') + strlen('apikey=')), '&');
        $matrix_key = rtrim(substr($matrix_key, strpos($matrix_key, 'apikey=') + strlen('apikey=')), '&');
        $route_key = rtrim(substr($route_key, strpos($route_key, 'apikey=') + strlen('apikey=')), '&');

        return ['geocode_key' => $geo_key, 'matrix_key' => $matrix_key, 'route_key' => $route_key];
    }

    /**
     * @inheritdoc
     */
    public function getCordsByAddress($address): ?array
    {
        $address = trim($address);

        $has_address = $this->addressContract->where('address', '=', $address)->findFirst(['lat', 'lut']);

        if ($has_address) {
            $coords = $has_address->getAttributes();
        } else {
            $geoObject = Geo::geocode($address);

            if (isset($geoObject['GeoObjectCollection']['featureMember'][0]) && is_array($geoObject)) {
                $cords = explode(' ', $geoObject['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);

                if (!$cords) {
                    return null;
                }

                $coords = [
                    'lat' => $cords[1],
                    'lut' => $cords[0]
                ];
            }
        }

        return $coords ?? null;
    }

    /**
     * @param $lat
     * @param $lut
     * @return string
     */
    public function getAddressByCords($lat, $lut): string
    {
        $has_address = $this->addressContract
            ->where('lat', '=', $lat)
            ->where('lut', '=', $lut)
            ->findFirst(['lat', 'lut', 'address']);

        if ($has_address) {
            $address = $has_address->address;
        } else {
            $geoObject = Geo::geocode("$lat $lut");

            if (isset($geoObject['GeoObjectCollection']['featureMember'][0]) && is_array($geoObject)) {
                $address = $geoObject['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
            }
        }

        return $address ?? '';
    }
}
