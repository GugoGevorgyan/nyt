<?php

declare(strict_types=1);


namespace Src\Listeners\Order\Calc\Utils;

use Src\Core\Enums\ConstAreaType;
use Src\Core\Enums\ConstRedis;
use Src\Core\Enums\ConstTariffType;

/**
 * Class RealTariffDetect
 * @package Src\Listeners\Order\Calc
 * @property null|array $tariffs = []
 */
trait RealTariffDetect
{
    /**
     * @return void
     */
    protected function getTariff(): void
    {
        $this->detectCurrentTariff();
        $this->setMinimalDistanceDuration();
    }

    /**
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function detectCurrentTariff(): void
    {
        if ($this->initialTariffType() === ConstTariffType::DESTINATION()->getValue()) {
            $this->doubleDestinationTariff();
            return;
        }

        if ($this->initialTariffType() === ConstTariffType::RENTAL()->getValue()) {
            $this->getRentTariffs();
            return;
        }

        if ($this->tariffs['second_tariff'] && $this->tariffs['initial_tariff']['current_tariff']['area_id']) {
            $this->doubleCurrentTariff();
            return;
        }

        if (!$this->tariffs['second_tariff'] && $this->tariffs['initial_tariff']['current_tariff']['area_id']) {
            $this->singleCurrentInTariff();
            return;
        }

        if (!$this->tariffs['second_tariff'] && !$this->tariffs['initial_tariff']['current_tariff']['area_id']) {
            $this->singleOutTariff();
        }
    }

    /**
     * @return void
     */
    protected function doubleDestinationTariff(): void
    {
        $key = ConstRedis::order_calc_data($this->driver->current_order->order_id);
        $dest_tariff = $this->redis->hmget($key, ['destination_tariff'])[0];

        if ($dest_tariff) {
            $this->tariffs['initial_tariff'] = igbinary_unserialize($dest_tariff);
        } else {
            $this->tariffs['initial_tariff']->load('current_tariff');
            $this->tariffs['initial_tariff']['current_tariff']
                ->load([
                    'from_area' => fn($query) => $query->select(['area_id', 'region', 'area']),
                    'to_area' => fn($query) => $query->select(['area_id', 'region', 'area'])
                ]);
            $this->redis->hmset($key, ['destination_tariff' => igbinary_serialize($this->tariffs['initial_tariff'])]);
        }
    }

    /**
     * @return void
     */
    protected function getRentTariffs(): void
    {
        [$in_point, $diff_past] = $this->rentCriterion($this->driver->process->travel_time);

        if (0 < $diff_past && !$in_point) {
            $behind = $this->rentContract
                ->with([
                    'alt_behind' => fn($query) => $query->select([
                        'tariff_region_behind_id',
                        'price_type_id',
                        'price_km',
                        'price_min',
                        'minimal_distance_value',
                        'minimal_duration_value',
                        'merge_km_minute'
                    ]),
                    'alt_destination' => fn($query) => $query->select([
                        'tariff_destination_id',
                        'price_type_id',
                        'destination_from_id',
                        'destination_to_id',
                        'price'
                    ]),
                ])
                ->find($this->tariffs['initial_tariff']['current_tariff']['tariff_rent_id']);

            $this->tariffs['initial_tariff']['rent_current'] = $behind['alt_behind'] ? $behind['alt_behind'][0] : [];
            $this->tariffs['initial_tariff']['rent_destinations'] = $behind['alt_destination'] ?: [];
            return;
        }

        if (0 < $diff_past) {
            $regional = $this->rentContract
                ->with([
                    'alt_region' => fn($query) => $query->select([
                        'tariff_region_city_id',
                        'price_type_id',
                        'price_km',
                        'price_min',
                        'minimal_distance_value',
                        'minimal_duration_value',
                        'merge_km_minute'
                    ])
                ])
                ->find($this->tariffs['initial_tariff']['current_tariff']['tariff_rent_id']);

            $this->tariffs['initial_tariff']['rent_current'] = $regional['alt_region'] ? $regional['alt_region'][0] : [];
            return;
        }

        if (!$in_point) {
            $destinations = $this->rentContract
                ->with([
                    'alt_destination' => fn($query) => $query->select([
                        'tariff_destination_id',
                        'price_type_id',
                        'destination_from_id',
                        'destination_to_id',
                        'price'
                    ]),
                    'alt_behind' => fn($query) => $query->select([
                        'tariff_region_behind_id',
                        'price_type_id',
                        'price_km',
                        'price_min',
                        'minimal_distance_value',
                        'minimal_duration_value',
                        'merge_km_minute'
                    ]),
                ])
                ->find($this->tariffs['initial_tariff']['current_tariff']['tariff_rent_id']);

            $this->tariffs['initial_tariff']['rent_current'] = $destinations['alt_region'] ? $destinations['alt_region'][0] : [];
            $this->tariffs['initial_tariff']['rent_destinations'] = $destinations ? $destinations['alt_destination'] : [];
            return;
        }

        $this->tariffs['initial_tariff']['current_tariff'] = $this->rentContract
            ->where('tariff_id', '=', $this->tariffs['initial_tariff']['tariff_id'])
            ->findFirst(['tariff_rent_id', 'tariff_id', 'area_id', 'price_type_id', 'hours']);
    }

    /**
     * @return mixed|void
     */
    private function doubleCurrentTariff()
    {
        $area = $this->areaContract->find($this->tariffs['initial_tariff']['current_tariff']['area_id'], ['area_id', 'area']);
        $from_in_polygon = $this->geoService->pointInPolygon($area->area, $this->cord);

        if ($from_in_polygon) {
            unset($this->tariffs['initial_tariff']['current_tariff']['behind'], $this->tariffs['initial_tariff']['area']);
            return $this->tariffs['initial_tariff'];
        }

        $behind = $this->tariffs['initial_tariff']['current_tariff']['behind'];
        unset($this->tariffs['initial_tariff']['area'], $this->tariffs['initial_tariff']['current_tariff']);
        $this->tariffs['initial_tariff']['current_tariff'] = $behind;
    }

    /**
     * @return void
     */
    private function singleCurrentInTariff(): void
    {
        $area = $this->areaContract->find($this->tariffs['initial_tariff']['current_tariff']['area_id'], ['area_id', 'area']);
        $from_in_polygon = $this->geoService->pointInPolygon($area->area, $this->cord);

        if ($from_in_polygon) {
            unset($this->tariffs['initial_tariff']['current_tariff']['behind'], $this->tariffs['initial_tariff']['area']);
            return;
        }

        $behind = $this->tariffs['initial_tariff']['current_tariff']['behind'];
        unset($this->tariffs['initial_tariff']['area'], $this->tariffs['initial_tariff']['current_tariff']);
        $this->tariffs['initial_tariff']['current_tariff'] = $behind;
    }

    /**
     * @return void
     */
    private function singleOutTariff(): void
    {
        $areas = $this->areaContract
            ->where('type', '=', ConstAreaType::REGION()->getValue())
            ->whereJsonContains('region->ids', $this->tariffs['initial_tariff']['region->ids'])
            ->findAll();

        if ($areas->count()) {
            foreach ($areas as $area) {
                $from_in_polygon = $this->geoService->pointInPolygon($area->area, $this->cord);

                if ($from_in_polygon) {
                    $area->load(['tariff' => fn($query) => $query->select('*')]); //@todo performance
                    if (!$area->tariff) {
                        continue;
                    }
                    $this->tariffs['initial_tariff'] = $area->tariff;
                    $area->tariff->load('tariff_region');//@todo performance
                    unset($this->tariffs['initial_tariff']['current_tariff']['behind']);
                    $this->tariffs['initial_tariff']['current_tariff'] = $area->tariff->tariff_region;
                    unset($this->tariffs['initial_tariff']['tariff_region']);
                    break;
                }
            }
        } else {
            unset($this->tariffs['initial_tariff']['current_tariff']['behind'], $this->tariffs['initial_tariff']['area']);
        }
    }
}
