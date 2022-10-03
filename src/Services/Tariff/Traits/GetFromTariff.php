<?php

declare(strict_types=1);


namespace Src\Services\Tariff\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Enums\ConstAreaType;
use Src\Core\Enums\ConstTariffType;
use Src\Exceptions\Lexcept;
use Src\Models\Tariff\Tariff;
use Src\Models\Tariff\TariffRent;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\City\CityContract;
use Src\Repositories\Region\RegionContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

use function in_array;

/**
 * Trait GetFromTariff
 *
 * @package Src\Services\Tariff\Traits
 * @property GeocodeServiceContract $geoService
 * @property TariffContract $tariffContract
 * @property RegionContract $regionContract
 * @property CityContract $cityContract
 * @property AreaContract $areaContract
 */
trait GetFromTariff
{
    /**
     * @return array|null
     * @throws Lexcept
     */
    protected function getCompanyTariff(): ?array
    {
        return $this->getClientTariff($this->payCompanyId);
    }

    /**
     * @param  int|null  $company
     * @return null|array{from_tariff: int, to_tariff: null|int, behind: int, distance_data: array}
     * @noinspection MultipleReturnStatementsInspection
     * @throws Lexcept
     */
    protected function getClientTariff(int $company = null): ?array
    {
        if ($this->toCoordinates) {
            // DESTINATION TARIFF
            $tariff_id = $this->destinationTariff();

            if ($tariff_id && $tariff_id['from_tariff']) {
                return $tariff_id;
            }
        }

        // BEHIND TARIFFS
        $zone_tariff = session('order.from_region_id') ? $this->detectBehindTariff() : null;

        if ($zone_tariff && $zone_tariff['from_tariff']) {
            return $zone_tariff;
        }

        // REGION CITY TARIFF
        $region_tariff = $this->getRightTariffByRegionCity('tariff_region', $this->regionId, $this->cityId);

        if ($region_tariff) {
            $distance = $this->toCoordinates ? $this->getDistance() : [];
            return $this->getTariffs($region_tariff, null, $distance);
        }

        if ($company) {
            throw new Lexcept(trans('messages.company_tariff_error'), 422);
        }

        // DEFAULT TARIFF
        return $this->defaultTariff();
    }

    /**
     * @return array|null
     */
    protected function destinationTariff(): ?array
    {
        $dest_tariffs = $this->getDestinationTariffs($this->payCompanyId);

        return $dest_tariffs->count() > 0 ? $this->detectIsDestinationTariff($dest_tariffs) : null;
    }

    /**
     * @param  int|null  $company
     * @return Collection
     */
    protected function getDestinationTariffs(int $company = null): Collection
    {
        $region = session('order.from_region_id') ?: null;

        return $this->tariffContract
            ->where('car_class_id', '=', $this->carClassId)
            ->whereHas('tariff_type', fn(Builder $type_query) => $type_query->where('type', '=', ConstTariffType::DESTINATION()->getValue()))
            ->when(
                $company,
                fn(Builder $query, $company) => $query
                    ->whereHas('companies', fn(Builder $corporate_query) => $corporate_query->where('company_tariff.company_id', '=', $company))
            )
            ->when(!$company, fn(Builder $query) => $query->doesntHave('companies'))
            ->when($region, fn(Builder $query) => $query->whereJsonContains('region->ids', $region))
            ->with([
                'tariff_destination' => fn(HasOne $destination_query) => $destination_query->select([
                    'tariff_destination_id',
                    'tariff_id',
                    'price',
                    'free_wait_stop_minutes',
                    'paid_wait_stop_minute',
                    'destination_from_id',
                    'destination_to_id'
                ])->with([
                    'from_area' => fn(BelongsTo $area_query) => $area_query->select(['area_id', 'name', 'area']),
                    'to_area' => fn(BelongsTo $area_query) => $area_query->select(['area_id', 'name', 'area'])
                ])
            ])
            ->findAll();
    }

    /**
     * @param $tariffs
     * @return array|null
     */
    #[ArrayShape([
        'from_tariff' => 'int',
        'to_tariff' => '\int|null',
        'behind' => '\int|null',
        'distance_data' => '\array|null'
    ])] protected function detectIsDestinationTariff($tariffs): ?array
    {
        $tariff_id = null;

        foreach ($tariffs as $destination) {
            $from_area = $destination->tariff_destination->from_area->area;
            $to_area = $destination->tariff_destination->to_area->area;

            $from_result = $this->geoService->pointInPolygon($from_area, $this->fromCoordinates);
            $to_result = $this->geoService->pointInPolygon($to_area, $this->toCoordinates ?: []);

            if ($from_result && $to_result) {
                $tariff_id = $destination->tariff_id;
                break;
            }
        }

        $distance = $this->getDistance();
        return $this->getTariffs((int)$tariff_id, null, $distance);
    }

    /**
     * @return array|null
     */
    protected function getDistance(): ?array
    {
        if (session()->exists('matrix_road')) {
            return session('matrix_road');
        }

        $diff_minute = $this->orderTime ? Carbon::parse($this->orderTime)->diffInMinutes(now()) : null;
        $micro_time_order = Carbon::now()->addMinutes($diff_minute)->timestamp;

        $matrix = $this->geoService->roadCalculation($this->fromCoordinates, $this->toCoordinates, $micro_time_order);
        session()->now('matrix_road', $matrix);

        return $matrix;
    }

    /**
     * @param  int  $from
     * @param  int|null  $to
     * @param  array|null  $distance
     * @param  int|null  $behind
     * @return array
     */
    #[ArrayShape([
        'from_tariff' => 'int',
        'to_tariff' => 'int|null',
        'behind' => 'int|null',
        'distance_data' => 'array|null'
    ])]
    protected function getTariffs(int $from, int $to = null, array $distance = null, int $behind = null): array
    {
        return ['from_tariff' => $from, 'to_tariff' => $to, 'behind' => $behind, 'distance_data' => $distance];
    }

    /**
     * @return array|null
     * @throws Lexcept
     */
    protected function detectBehindTariff(): ?array
    {
        $areas = $this->areaContract
            ->has('behind_tariff')
            ->where('type', '=', ConstAreaType::REGION()->getValue())
            ->whereJsonContains('region->ids', session('order.from_region_id'))
            ->whereHas('tariff', fn(Builder $tariff_query) => $tariff_query->where('car_class_id', '=', $this->carClassId))
            ->findAll(['area_id', 'area', 'type', 'region']);

        if ($areas->count() < 1) {
            return null;
        }

        $behind_tariff_from = $this->detectBehindPositionInPolygon($this->fromCoordinates, $areas);
        $behind_tariff_to = $this->toCoordinates ? $this->detectBehindPositionInPolygon($this->toCoordinates, $areas) : null;

        if (!isset($behind_tariff_from['behind'], $behind_tariff_to['behind'])) {
            return null;
        }

        if ($behind_tariff_to['behind'] && !$behind_tariff_from['behind']) {
            return $this->behindTariffFrom($behind_tariff_from, $behind_tariff_to, $areas);
        }

        if (!$behind_tariff_to['behind'] && $behind_tariff_from['behind']) {
            return $this->behindTariffFromReverse($behind_tariff_to, $behind_tariff_from, $areas);
        }

        return null;
    }

    /**
     * @param  array  $coordinate
     * @param  Collection  $areas
     * @return array|null
     * @throws Lexcept
     */
    protected function detectBehindPositionInPolygon(array $coordinate, Collection $areas): ?array
    {
        $behind_tariff = null;
        $behind = false;

        // POINT IN POLYGON
        foreach ($areas as $area) {
            $in_polygon = $this->geoService->pointInPolygon($area->area, $coordinate);

            if ($in_polygon) {
                $behind_tariff = $area
                    ->load([
                        'tariff' => fn(HasOneDeep $q_tariff) => $q_tariff
                            ->where('car_class_id', '=', $this->carClassId)
                            ->when($this->payCompany, fn(Builder $query) => $query
                                ->where('is_default', '=', false)
                                ->whereHas('companies', fn(Builder $query) => $query->where('companies.company_id', '=', $this->payCompanyId)))
                            ->select(['tariffs.tariff_id'])
                    ]);

                if ($behind_tariff->tariff) {
                    break;
                }
            } else {
                $behind_tariff = $area
                    ->load([
                        'tariff' => fn(HasOneDeep $q_tariff) => $q_tariff
                            ->where('car_class_id', '=', $this->carClassId)
                            ->with('tariff_behind')
                            ->when($this->payCompany, fn(Builder $query) => $query
                                ->where('is_default', '=', false)
                                ->whereHas('companies', fn(Builder $query) => $query->where('companies.company_id', '=', $this->payCompanyId)))
                            ->select(['tariffs.tariff_id'])
                    ]);

                if ($behind_tariff->tariff->tariff_behind) {
                    $behind = true;
                    break;
                }
            }
        }

        $tariff_id = $behind_tariff->tariff->tariff_id ?? null;

        if (!$tariff_id) {
            throw new Lexcept('Неправильный класс автомобиля', 400);
        }

        $area = $behind_tariff->area;

        return compact('tariff_id', 'behind', 'area');
    }

    /**
     * @param  array  $behind_tariff_from
     * @param $behind_tariff_to
     * @param  Collection  $areas
     * @return array|null
     */
    protected function behindTariffFrom(array $behind_tariff_from, $behind_tariff_to, Collection $areas): ?array
    {
        $behind = $this->detectBehindEarPoint($behind_tariff_from['behind'], $behind_tariff_to['behind'] ?? null);

        if ($this->toCoordinates) {
            $to_in_behind = $this->detectPositionBehindPolygon($this->toCoordinates, $areas, $behind_tariff_from['tariff_id']);

            if ($to_in_behind['tariff_id']) {
                return $this->getTariffs($behind_tariff_from['tariff_id'], $to_in_behind['tariff_id'], $this->getRoadABC($behind_tariff_from['area']), $behind);
            }
        }

        return $this->getTariffs($behind_tariff_from['tariff_id'], null, null, $behind);
    }

    /**
     * @param $behind_from
     * @param $behind_to
     * @return int
     */
    protected function detectBehindEarPoint($behind_from, $behind_to = null): int
    {
        if (!$behind_from && !$behind_to) {
            $behind = Tariff::BEHIND_NOTE;
        } elseif ($behind_from && $behind_to) {
            $behind = Tariff::BEHIND_DOUBLE;
        } elseif ($behind_to) {
            $behind = Tariff::BEHIND_TO;
        } elseif ($behind_from) {
            $behind = Tariff::BEHIND_FROM;
        } else {
            $behind = Tariff::BEHIND_NOTE;
        }

        return $behind;
    }

    /**
     * @param $coordinates
     * @param $areas
     * @param  null  $tariff_id
     * @return array
     */
    public function detectPositionBehindPolygon($coordinates, $areas, $tariff_id = null): array
    {
        $distance = [];

        foreach ($areas as $area_reverse) {
            $distance[] = $this->geoService->behindIntersectionCoordinateDistance($coordinates, $area_reverse->area);
        }

        $distance_behind = array_first($distance);

        $behind_tariffs = $this->tariffContract
            ->when($tariff_id, fn(Builder $query) => $query->where('tariff_id', '=', $tariff_id))
            ->has('tariff_behind')
            ->with([
                'tariff_behind' => fn(HasOne $q_behind) => $q_behind->select([
                    'tariff_region_behind.tariff_region_behind_id',
                    'tariff_region_behind.tariff_region_id',
                    'tariff_region_behind.zone_distance',
                    'tariff_region_behind.tariff_id',
                ])
            ])
            ->findAll(['tariff_id']);

        $tariff_id = null;

        foreach ($behind_tariffs as $behind_tariff) {
            if ($behind_tariff->tariff_behind->zone_distance >= $distance_behind) {
                $tariff_id = $behind_tariff->tariff_id;
                break;
            }
        }

        return compact('tariff_id');
    }

    /**
     * @param  array  $polygon
     * @return array
     */
    protected function getRoadABC(array $polygon): array
    {
        return $this->geoService->getRoadIntersectABC($this->fromCoordinates, $this->toCoordinates, $polygon); //@todo
    }

    /**
     * @param $behind_tariff_to
     * @param $behind_tariff_from
     * @param $areas
     * @return array|null
     * @noinspection MultipleReturnStatementsInspection
     */
    public function behindTariffFromReverse($behind_tariff_to, $behind_tariff_from, $areas): ?array
    {
        $fictional_tariff_from_behind = $this->detectPositionBehindPolygon($this->fromCoordinates, $areas, $behind_tariff_to['tariff_id']);

        if ($fictional_tariff_from_behind['tariff_id']) {
            if ($behind_tariff_to) {
                $area = $this->detectAreaByBehindRegionTariff($behind_tariff_to['tariff_id']);
                $distance_data = $this->getRoadABC($area);
                $behind = $this->detectBehindEarPoint($behind_tariff_from['behind'], $behind_tariff_to['behind'] ?? null);

                return $this->getTariffs($fictional_tariff_from_behind['tariff_id'], $behind_tariff_to['tariff_id'], $distance_data, $behind);
            }

            $to_in_behind = $this->detectPositionBehindPolygon($this->toCoordinates, $areas, $behind_tariff_to['tariff_id']);

            if ($to_in_behind['tariff_id'] && $to_in_behind['tariff_id'] === $fictional_tariff_from_behind['tariff_id']) {
                $distance = $this->getDistance();

                return $this->getTariffs($fictional_tariff_from_behind['tariff_id'], null, $distance);
            }

            return $this->getTariffs($fictional_tariff_from_behind['tariff_id']);
        }

        // @todo здесь ограничения для точки "А" чтобы они не взяли заказ из дальних мест, если хотите снять ограничение возвращайте отсюда тариф данной точки
        return null;
    }

    /**
     * @param $region_behind_tariff_id
     * @return array
     */
    public function detectAreaByBehindRegionTariff($region_behind_tariff_id): array
    {
        $area = $this->tariffContract->with('area')->find($region_behind_tariff_id);
        return $area->area->area;
    }

    /**
     * @param  string|null  $relation_name
     * @param  int|null  $region_id
     * @param  int|null  $city_id
     * @param  bool  $fictional
     * @return int|null
     */
    protected function getRightTariffByRegionCity(string $relation_name = null, int $region_id = null, int $city_id = null, bool $fictional = false): ?int
    {
        $tariff = $this->tariffContract
            ->where('car_class_id', '=', $this->carClassId)
            ->where('status', '=', 1)
            ->when($region_id, fn(Builder $query, $region_id) => $query->whereJsonContains('region->ids', $region_id))
            ->when(!$this->payCompany, fn(Builder $query) => $query->where('is_default', '=', 1))
            ->when($relation_name, fn(Builder $query, $relation_name) => $query->has($relation_name))
            ->when(
                $this->payCompany,
                fn(Builder $query) => $query
                    ->where('is_default', '=', false)
                    ->whereHas('companies', fn(Builder $company_query) => $company_query->where('companies.company_id', '=', $this->payCompanyId))
            );

        $tariff = $fictional
            ? $tariff->findFirst(['tariff_id', 'car_class_id', 'region', 'city', 'is_default', 'status'])
            : $tariff->findAll(['tariff_id', 'car_class_id', 'region', 'city', 'is_default', 'status'])
                ->filter(static function ($items) use ($city_id) {
                    if ($items->city['ids'] && $city_id && in_array($city_id, $items->city['ids'], true)) {
                        return $items;
                    }

                    return $items;
                })
                ->first();

        return $tariff ? (int)$tariff->tariff_id : null;
    }

    /**
     * @return array|null
     */
    protected function defaultTariff(): ?array
    {
        $default_tariff = $this->tariffContract
            ->where('car_class_id', '=', $this->carClassId)
            ->where('is_default', '=', true)
            ->whereJsonContains('region->ids', $this->regionId)
            ->when($this->cityId, fn(Builder $query) => $query->whereJsonContains('city->ids', $this->cityId))
            ->doesntHave('companies')
            ->findFirst(['tariff_id']);

        return $default_tariff ? $this->getTariffs((int)$default_tariff->tariff_id) : null;
    }

    /**
     * @return array|null
     */
    protected function getRentTariff(): ?array
    {
        $tariff = $this->tariffContract
            ->where('tariff_type_id', '=', ConstTariffType::RENTAL()->getValue())
            ->where('car_class_id', '=', $this->carClassId)
            ->where('status', '=', 1)
            ->when($this->regionId, fn(Builder $query, $region_id) => $query->whereJsonContains('region->ids', $region_id))
            ->when($this->cityId, fn(Builder $query, $city_id) => $query->whereJsonContains('city->ids', $city_id))
            ->whereHasMorph('current_tariff', [(new TariffRent())->getMap()], fn(Builder $query) => $query->where('hours', '=', $this->rentTime))
            ->findFirst(['tariff_id', 'tariffable_id', 'tariffable_type']);

        if (!$tariff) {
            return null;
        }

        $area = $this->tariffContract->getTariffWithArea($tariff->tariff_id);
        $in_point = $this->geoService->pointInPolygon($area->area['area'], $this->fromCoordinates);

        if (!$in_point) {
            return null;
        }

        return $this->getTariffs($tariff->tariff_id);
    }

    /**
     * @return array
     */
    protected function getRegionCity(): array
    {
        $to_region_name = $this->geoService->getRightGeocode($this->toCoordinates, true);
        $region = $this->regionContract->where('name', '=', $to_region_name['province'])->findFirst(['name', 'region_id']);
        $city = $this->cityContract->where('name', '=', $to_region_name['locality'])->findFirst(['name', 'city_id']);

        $region_id = $region->region_id ?? null;
        $city_id = $city->city_id ?? null;

        return compact('region_id', 'city_id');
    }
}
