<?php

declare(strict_types=1);


namespace Src\Repositories\Tariff;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JsonException;
use Repository\Repositories\BaseRepository;
use Src\Models\Tariff\Tariff;

/**
 * Class TariffRepository
 * @package Src\Repositories\Tariff
 */
class TariffRepository extends BaseRepository implements TariffContract
{
    /**
     * TariffRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Tariff::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('tariffs');
    }

    /**
     * @inheritDoc
     */
    public function getTariffByOrder($order_id): Collection
    {
        $order_info = $this
            ->whereHas('initial', fn(Builder $query) => $query->where('order_id', '=', $order_id))
            ->with([
                'tariff_behind' => fn($query) => $query->select([
                    'tariff_region_behind_id',
                    'tariff_region_id',
                    'tariff_id',
                    'zone_distance',
                    'price_km',
                    'price_min',
                    'sitting_fee',
                    'cancel_fee',
                    'sit_fix_price',
                    'sit_price_km',
                    'sit_price_minute',
                    'free_wait_stop_minutes',
                    'paid_wait_stop_minute',
                    'enable_speed_wait',
                    'speed_wait_limit',
                    'enable_speed_wait',
                    'speed_wait_price_minute',
                    'change_initial_price_percent',
                    'merge_km_minute',
                    'sit_type_id',
                    'price_type_id',
                ]),
                'tariff_destination' => fn($query) => $query->select([
                    'tariff_destination_id',
                    'tariff_id',
                    'price',
                    'destination_from_id',
                    'destination_to_id',
                    'free_wait_stop_minutes',
                    'paid_wait_stop_minute',
                    'change_initial_price_percent',
                ]),
                'tariff_region' => fn($query) => $query->select([
                    'tariff_region_city_id',
                    'tariff_id',
                    'area_id',
                    'price_km',
                    'price_min',
                    'sitting_fee',
                    'cancel_fee',
                    'sit_fix_price',
                    'sit_price_km',
                    'sit_price_minute',
                    'free_wait_stop_minutes',
                    'paid_wait_stop_minute',
                    'enable_speed_wait',
                    'speed_wait_limit',
                    'enable_speed_wait',
                    'speed_wait_price_minute',
                    'minimal_distance_value',
                    'minimal_duration_value',
                    'change_initial_price_percent',
                    'merge_km_minute',
                    'sit_type_id',
                    'price_type_id',
                ]),
                'area' => fn($query) => $query->select([
                    'areas.area_id',
                    'areas.type',
                    'areas.area',
                ])
            ])
            ->findFirst([
                'tariff_id',
                'country_id',
                'region',
                'city',
                'name',
                'car_class_id',
                'tariff_type_id',
                'payment_type_id',
                'paid_parking_client',
                'tool_roads_client',
                'is_default',
                'status',
                'free_wait_minutes',
                'paid_wait_minute',
                'minimal_price',
                'rounding_price',
                'date_from',
                'date_to',
                'tariffable_id',
                'tariffable_type',
                'diff_percent'
            ])
            ->toArray();

        return collect($order_info);
    }

    /**
     * @param $tariff_id
     * @param  array  $tariff_values
     * @return Tariff|null
     * @throws JsonException
     */
    public function getTariffWithArea($tariff_id, array $tariff_values = ['*']): ?Tariff
    {
        $tariff = $this
            ->with(['current_tariff'])
            ->find($tariff_id, $tariff_values);

        if (!$tariff) {
            return null;
        }

        $area = array_map(static fn($value) => (array)$value,
            DB::select('select distinct t.tariff_id, a.area, a.area_id, a.type from (tariffs as t, areas as a)
                        inner join (tariff_rents, tariff_regions_cities, areas)
                            on tariff_regions_cities.area_id = a.area_id and tariff_regions_cities.tariff_id = t.tariff_id
                            or tariff_rents.area_id = a.area_id and tariff_rents.tariff_id = t.tariff_id
                        where t.tariff_id = '.$tariff_id));

        $tariff->area = $area
            ? [
                'area_id' => $area[0]['area_id'],
                'type' => $area[0]['type'],
                'area' => json_decode($area[0]['area'], true, 512, JSON_THROW_ON_ERROR),
            ] : [];

        return $tariff;
    }
}
