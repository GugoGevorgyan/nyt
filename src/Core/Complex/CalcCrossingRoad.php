<?php

declare(strict_types=1);

namespace Src\Core\Complex;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use JsonException;
use Src\Core\Enums\ConstAreaType;
use Src\Core\Enums\ConstTariffType;
use Src\Core\Traits\Complex;
use Src\Models\Order\OrderProcess;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\CompletedOrder\CompletedOrderContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Services\Geocode\GeocodeServiceContract;

use function count;

/**
 * Class CalcCrossingRoad
 *
 * @method static complex(int $order_id, int $tariff_id, int $region_id, array $cords, array $road, OrderProcess $process): ? array
 *
 * @property AreaContract $areaContract
 * @property TariffContract $tariffContract
 * @property GeocodeServiceContract $geocodeService
 * @property CompletedOrderContract $completedOrderContract
 */
final class CalcCrossingRoad
{
    use Complex;

    /**
     * @var array{in_price: int|float|string, in_distance_price: int|float|string, in_duration_price: int|float|string, out_price: int|float|string, out_distance_price: int|float|string, out_duration_price: int|float|string, in_distance: int|float|string, out_distance: int|float|string, in_duration: array, out_duration: array, in_trajectory: array, out_trajectory: array, cost: int|float|string}
     */
    private static array $inOutCalc = [
        'in_price' => 0,
        'in_distance_price' => 0,
        'in_duration_price' => 0,
        'out_price' => 0,
        'out_distance_price' => 0,
        'out_duration_price' => 0,
        'in_distance' => 0,
        'out_distance' => 0,
        'in_duration' => ['start' => 0, 'end' => 0, 'time' => 0],
        'out_duration' => ['start' => 0, 'end' => 0, 'time' => 0],
        'in_trajectory' => [],
        'out_trajectory' => [],
        'cost' => 0,
    ];
    /**
     * @var array|null[]
     */
    private static array $tariffs = ['first' => null, 'second' => null];
    /**
     * @var bool
     */
    private static bool $firstInArea = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected int $orderId,
        protected int $initialTariffId,
        protected int $regionId,
        protected array $cords,
        protected array $road,
        protected OrderProcess $process
    ) {
    }

    /**
     * @param  AreaContract  $areaContract
     * @param  TariffContract  $tariffContract
     * @param  CompletedOrderContract  $completedOrderContract
     * @param  GeocodeServiceContract  $geocodeService
     * @return array|null
     * @throws JsonException
     */
    public function handle(
        AreaContract $areaContract,
        TariffContract $tariffContract,
        CompletedOrderContract $completedOrderContract,
        GeocodeServiceContract $geocodeService
    ): ?array {
        if ($this->tariffContract->where('tariff_type_id', '=',
            ConstTariffType::RENTAL()->getValue())->find($this->initialTariffId)) { // @TODO FIX RENT TARIFF STATISTIC
            return null;
        }

        $this->separateDistanceData();

        if ((!self::$inOutCalc['in_distance'] && !self::$inOutCalc['out_distance']) || !self::$inOutCalc['cost']) {
            $this->resetPass();
            return null;
        }

        return $this->resetPass();
    }

    /**
     * @return void
     * @throws JsonException
     */
    protected function separateDistanceData(): void
    {
        $area = $this->areaContract
            ->whereJsonContains('region->ids', $this->regionId)
            ->where('type', '=', ConstAreaType::REGION()->getValue())
            ->findFirst();

        if (!$area) {
            return;
        }

        $first_in = $this->geocodeService->pointInPolygon($area->area, $this->cords['start']);
        $second_in = $this->geocodeService->pointInPolygon($area->area, $this->cords['end']);

        if ($first_in && !$second_in) {
            $tariff = $this->getCalcTariffs();

            $tariff['area_id'] && $tariff['area_id'] === $area->area_id ? $this->iterateInRoad($area->area, $this->road) : null;
        } elseif (!$first_in && $second_in) {
            self::$firstInArea = false;
            $this->iterateOutRoad($area->area, $this->road);
        } else {
            return;
        }

        if (($first_in && !$second_in) || (!$first_in && $second_in)) {
            $this->calcInOut();
        }
    }

    /**
     * @param  bool  $first
     * @return array
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function getCalcTariffs(bool $first = true): array
    {
        // A -> B || B -> A
        if (($first && self::$firstInArea) || ($first && !self::$firstInArea)) {
            $tariff = $this->tariffContract
                ->with(['current_tariff' => fn(MorphTo $query) => $query->select(['*'])])
                ->find($this->initialTariffId, ['tariff_id', 'tariff_type_id', 'car_class_id', 'tariffable_id', 'tariffable_type'])
                ->current_tariff;

            return $tariff ? $tariff->toArray() : [];
        }

        // B -> A || A -> B
        if ((!$first && self::$firstInArea) || (!$first && !self::$firstInArea)) {
            $tariff = $this->tariffContract
                ->with(['tariff_behind' => fn(HasOne $query) => $query->select(['*'])])
                ->find($this->initialTariffId, ['tariff_id', 'tariff_type_id', 'car_class_id', 'tariffable_id', 'tariffable_type'])
                ->tariff_behind;

            return $tariff ? $tariff->toArray() : [];
        }

        return [];
    }

    /**
     * @param  array  $area
     * @param  array  $real_road
     * @throws JsonException
     */
    protected function iterateInRoad(array $area, array $real_road): void
    {
        $iterate = 0;

        self::$tariffs['first'] = $this->getCalcTariffs();

        foreach ($real_road as $key => $road) {
            ++$iterate;

            $in_point = $this->geocodeService->pointInPolygon($area, $road);

            if ($in_point) {
                if (!$prev = $real_road[$key - 1] ?? null) {
                    self::$inOutCalc['in_duration']['start'] = Carbon::parse($road['date']);
                }

                self::$inOutCalc['in_distance'] += $prev ? distance_cords($prev['lat'], $prev['lut'], $road['lat'], $road['lut'], 'm') : 0;
                self::$inOutCalc['in_trajectory'][] = ['lat' => $road['lat'], 'lut' => $road['lut'], 'date' => $road['date']];
                self::$inOutCalc['in_duration']['end'] = Carbon::parse($road['date']);
            } else {
                $short_road = array_splice($real_road, $iterate - 1, count($real_road) - ($iterate - 1));
                $this->iterateOutRoad($area, $short_road);

                break;
            }
        }
    }

    /**
     * @param  array  $area
     * @param  array  $real_road
     * @throws JsonException
     */
    protected function iterateOutRoad(array $area, array $real_road): void
    {
        self::$tariffs['second'] = $this->getCalcTariffs(false);
        $iterate = 0;

        foreach ($real_road as $key => $road) {
            ++$iterate;

            $in_point = $this->geocodeService->pointInPolygon($area, $road);

            if ($in_point) {
                $short_road = array_splice($real_road, $iterate - 1, count($real_road) - ($iterate - 1));
                $this->iterateInRoad($area, $short_road);

                break;
            }

            if (!$prev = $real_road[$key - 1] ?? null) {
                self::$inOutCalc['out_duration']['start'] = Carbon::parse($road['date']);
            }

            self::$inOutCalc['out_distance'] += $prev ? distance_cords($prev['lat'], $prev['lut'], $road['lat'], $road['lut'], 'm') : 0;
            self::$inOutCalc['out_trajectory'][] = ['lat' => $road['lat'], 'lut' => $road['lut'], 'date' => $road['date']];
            self::$inOutCalc['out_duration']['end'] = Carbon::parse($road['date']);
        }
    }

    /**
     * All Result Calculate In && Out
     */
    protected function calcInOut(): void
    {
        $first_remainder_seconds = Carbon::parse(self::$inOutCalc['in_duration']['start'])->diffInSeconds(Carbon::parse(self::$inOutCalc['in_duration']['end']));
        $first_duration = (int)floor($first_remainder_seconds / 60) - self::$tariffs['first']['minimal_duration_value'];
        $first_distance = round_d(self::$inOutCalc['in_distance'] - self::$tariffs['first']['minimal_distance_value']);
        $first_trajectory = self::$inOutCalc['in_trajectory'];

        $first_price = 0;
        $first_distance_price = 0;
        $first_duration_price = 0;

        switch (self::$tariffs['first']['price_type_id']) {
            case ConstTariffType::KM_AND_MIN()->getValue();
                $first_price += $first_distance * self::$tariffs['first']['price_km'];
                $first_price += $first_duration * self::$tariffs['first']['price_min'];
                $first_distance_price += $first_distance * self::$tariffs['first']['price_km'];
                $first_duration_price += $first_duration * self::$tariffs['first']['price_min'];
                break;
            case ConstTariffType::KM()->getValue();
                $first_price += $first_distance * self::$tariffs['first']['price_km'];
                $first_distance_price += $first_distance * self::$tariffs['first']['price_km'];
                break;
            case ConstTariffType::MINUTE()->getValue();
                $first_price += $first_duration * self::$tariffs['first']['price_min'];
                $first_duration_price += $first_duration * self::$tariffs['first']['price_min'];
                break;
            default:
        }

        self::$inOutCalc['in_distance'] = $first_distance;
        self::$inOutCalc['in_duration']['time'] = $first_duration;
        self::$inOutCalc['in_price'] = $first_price;
        self::$inOutCalc['in_distance_price'] = $first_distance_price;
        self::$inOutCalc['in_duration_price'] = $first_duration_price;
        self::$inOutCalc['in_trajectory'] = $first_trajectory;


        $second_remainder_seconds = Carbon::parse(self::$inOutCalc['out_duration']['start'])->diffInSeconds(Carbon::parse(self::$inOutCalc['out_duration']['end'])) - self::$tariffs['second']['minimal_duration_value'];
        $second_duration = round_t($second_remainder_seconds + fmod($first_remainder_seconds, 60));
        $second_distance = round_d(self::$inOutCalc['out_distance'] - self::$tariffs['second']['minimal_distance_value']) + 0.1;
        $second_trajectory = self::$inOutCalc['out_trajectory'];

        $second_price = 0;
        $second_distance_price = 0;
        $second_duration_price = 0;

        switch (self::$tariffs['second']['price_type_id']) {
            case ConstTariffType::KM_AND_MIN()->getValue();
                $second_price += $second_distance * self::$tariffs['second']['price_km'];
                $second_price += $second_duration * self::$tariffs['second']['price_min'];
                $second_distance_price += $second_distance * self::$tariffs['second']['price_km'];
                $second_duration_price += $second_duration * self::$tariffs['second']['price_min'];
                break;
            case ConstTariffType::KM()->getValue();
                $second_price += $second_distance * self::$tariffs['second']['price_km'];
                $second_distance_price += $second_distance * self::$tariffs['second']['price_km'];
                break;
            case ConstTariffType::MINUTE()->getValue();
                $second_price += $second_duration * self::$tariffs['second']['price_min'];
                $second_duration_price += $second_duration * self::$tariffs['second']['price_min'];
                break;
            default:
        }

        self::$inOutCalc['out_distance'] = $second_distance;
        self::$inOutCalc['out_duration']['time'] = $second_duration;
        self::$inOutCalc['out_price'] = $second_price;
        self::$inOutCalc['out_distance_price'] = $second_distance_price;
        self::$inOutCalc['out_duration_price'] = $second_duration_price;
        self::$inOutCalc['out_trajectory'] = $second_trajectory;

        self::$inOutCalc['cost'] = self::$inOutCalc['in_price'] + self::$inOutCalc['out_price'] + $this->plusMinimal()
            + $this->process->options_price
            + $this->process->pause_price
            + $this->process->waiting_price
            + $this->process->sitting_price;
    }

    /**
     * @return string|float
     */
    protected function plusMinimal(): float|string
    {
        if (self::$tariffs['first']['minimal_duration_value']
            || self::$tariffs['first']['minimal_distance_value']
            || self::$tariffs['second']['minimal_duration_value']
            || self::$tariffs['second']['minimal_distance_value']) {
            return $this->tariffContract->find($this->initialTariffId, ['tariff_id', 'minimal_price'])->minimal_price;
        }

        return 0.0;
    }

    /**
     * @return array
     */
    protected function resetPass(): array
    {
        $calc_data = self::$inOutCalc;

        self::$firstInArea = true;
        self::$tariffs = [];
        self::$inOutCalc = [
            'in_price' => 0,
            'in_distance_price' => 0,
            'in_duration_price' => 0,
            'out_price' => 0,
            'out_distance_price' => 0,
            'out_duration_price' => 0,
            'in_distance' => 0,
            'out_distance' => 0,
            'in_duration' => ['start' => 0, 'end' => 0, 'time' => 0],
            'out_duration' => ['start' => 0, 'end' => 0, 'time' => 0],
            'in_trajectory' => [],
            'out_trajectory' => [],
            'cost' => 0
        ];

        return $calc_data;
    }
}
