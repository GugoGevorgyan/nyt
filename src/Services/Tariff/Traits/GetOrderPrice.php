<?php

declare(strict_types=1);


namespace Src\Services\Tariff\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Src\Core\Enums\ConsTariffRounding;
use Src\Core\Enums\ConstTariffType;
use Src\Exceptions\Lexcept;
use Src\Models\Tariff\Tariff;
use Src\Models\Tariff\TariffPriceType;
use Src\Models\Tariff\TariffRent;
use Src\Repositories\Tariff\TariffContract;
use Src\Services\Geocode\GeocodeServiceContract;

/**
 * Trait GetOrderPrice
 * @package Src\Services\Tariff\Traits
 * @property TariffContract $tariffContract
 * @property GeocodeServiceContract $geoService
 */
trait GetOrderPrice
{
    /**
     * @param $tariffs
     * @return array
     */
    public function calculateOrderPrice($tariffs): array
    {
        $tariffs_data = $this->getTariffsData($tariffs['from_tariff'], $tariffs['to_tariff'], $tariffs['behind']);
        $redemption_minimals = $this->detectMinPrice($tariffs_data['from'], $tariffs_data['to'], $tariffs['distance_data']);
        $price_distance = $this->fromToDistanceDuration($tariffs_data['from'], $tariffs_data['to'], $tariffs['distance_data'], $redemption_minimals);
        $option_price = $this->calculateOptions($tariffs['from_tariff']);
        $sitting_price = $this->sitPriceCalc($tariffs, $tariffs_data);
        $this->roundingPrice($price_distance['price'], $tariffs_data['from']['rounding_price']);

        $sitting_fee = (bool)$sitting_price;

        $price = [
            'price' => $price_distance['price'],
            'distance' => $tariffs['distance_data']['distance'] ?? $tariffs['distance_data']['a_b_distance'] + $tariffs['distance_data']['b_c_distance'],
            'duration' => $tariffs['distance_data']['duration'] ?? $tariffs['distance_data']['a_b_duration'] + $tariffs['distance_data']['b_c_duration']
        ];

        $price['price'] += $option_price + $sitting_price;
        $price_distance['price'] += $option_price + $sitting_price;

        return $this->filteredReturnData($tariffs_data, $sitting_price, $option_price, $sitting_fee, $tariffs, $price_distance['price'], $price);
    }

    /**
     * @param $from_tariff
     * @param $to_tariff
     * @param  int|null  $behind
     * @return array[]
     */
    protected function getTariffsData($from_tariff, $to_tariff, int $behind = null): array
    {
        $from = $from_tariff
            ? $this->tariffContract->except(['created_at', 'updated_at'])->with([
                'current_tariff',
                'tariff_behind',
                'country:country_id,currency'
            ])->find($from_tariff)
            : $from_tariff;

        $to = $to_tariff
            ? $this->tariffContract->except(['created_at', 'updated_at'])->with(['current_tariff', 'tariff_behind'])->find($to_tariff)
            : $to_tariff;

        $from ? $from->currency = $from->country->currency ?? 'RUB' : '';

        if ($to_tariff && !($from && $to)) {
            if (Tariff::BEHIND_TO === $behind && $to->tariff_behind) {
                $to->current_tariff = $to->tariff_behind;
            } elseif ($from->tariff_behind) {
                $from->current_tariff = $from->tariff_behind;
            } else {
                $to->current_tariff = $to->tariff_behind;
            }
        }

        $from_data = $from->mergeAttributes($from->current_tariff, $from->country)
            ->except(['created_at', 'updated_at', 'tariffable_type', 'tariffable_id', 'name', 'current_tariff']);

        $to_data = $to
            ? $to->mergeAttributes($to->current_tariff)->except(['created_at', 'updated_at', 'tariffable_type', 'tariffable_id', 'name', 'current_tariff'])
            : $to_tariff;

        $from = $from_data ? $from_data->toArray() : [];
        $to = $to_data ? $to_data->toArray() : [];

        return compact('from', 'to');
    }

    /**
     * @param  array  $from_tariff
     * @param  array  $to_tariff
     * @param  array  $distances
     * @return array
     */
    #[Pure] #[ArrayShape([
        'distance' => 'int|mixed',
        'duration' => 'int|mixed'
    ])]
    protected function detectMinPrice(array $from_tariff, array $to_tariff = [], array $distances = []): array
    {
        $redemption_distance = 0;
        $redemption_duration = 0;

        if (isset($distances['a_b_distance'])) {
            [$redemption_distance, $redemption_duration] = $this->detectMinPriceWithCrossing($from_tariff, $to_tariff, $distances);

            return ['distance' => $redemption_distance, 'duration' => $redemption_duration];
        }

        if (isset($from_tariff['minimal_distance_value'], $from_tariff['minimal_duration_value'])) {
            if (0 !== $from_tariff['minimal_distance_value'] && $distances['distance'] > $from_tariff['minimal_distance_value']) {
                $redemption_distance = $distances['distance'] - $from_tariff['minimal_distance_value'];
            }

            if (0 !== $from_tariff['minimal_duration_value'] && $distances['duration'] > $from_tariff['minimal_duration_value']) {
                $redemption_duration = $distances['duration'] - $from_tariff['minimal_duration_value'];
            }
        } else {
            $redemption_distance = $distances['distance'];
            $redemption_duration = $distances['duration'];
        }

        return ['distance' => $redemption_distance, 'duration' => $redemption_duration];
    }

    /**
     * @param  array  $from_tariff
     * @param  array  $to_tariff
     * @param  array  $distances
     * @return array
     */
    protected function detectMinPriceWithCrossing(array $from_tariff, array $to_tariff = [], array $distances = []): array
    {
        if (0 !== $from_tariff['minimal_distance_value'] && $distances['a_b_distance'] > $from_tariff['minimal_distance_value']) {
            $redemption_distance = $distances['a_b_distance'] - $from_tariff['minimal_distance_value'];
        }

        if (0 !== $from_tariff['minimal_duration_value'] && $distances['a_b_duration'] > $from_tariff['minimal_duration_value']) {
            $redemption_duration = $distances['a_b_duration'] - $from_tariff['minimal_duration_value'];
        }

        if (0 !== $to_tariff['minimal_duration_value'] && $distances['b_c_duration'] > $to_tariff['minimal_duration_value']) {
            $redemption_duration = $distances['b_c_duration'] - $to_tariff['minimal_duration_value'];
        }

        if (0 !== $to_tariff['minimal_distance_value'] && $distances['b_c_distance'] > $to_tariff['minimal_distance_value']) {
            $redemption_distance = $distances['b_c_distance'] - $to_tariff['minimal_distance_value'];
        }

        return [$redemption_distance ?? 0, $redemption_duration ?? 0];
    }

    /**
     * @param  array  $from_tariff
     * @param  array  $to_tariff
     * @param  array  $distance_data
     * @param  array  $redemptions
     * @return array
     */
    protected function fromToDistanceDuration(array $from_tariff, array $to_tariff = [], array $distance_data = [], array $redemptions = []): array
    {
        $redemption_distance = $redemptions['distance']; // KM
        $redemption_duration = $redemptions['duration']; //MIN

        $distance = $redemption_distance ?: $distance_data['distance'] ?? $distance_data['a_b_distance'];
        $duration = $redemption_duration ?: $distance_data['duration'] ?? $distance_data['a_b_duration'];

        $price = $this->fromToDistanceDurationSwitcher($from_tariff, $to_tariff, $distance_data, $distance, $duration);
        $this->distanceDurationFilter($distance, $duration, $from_tariff, $to_tariff, $distance_data);
        $this->minDistanceDurationPrice($price, $from_tariff, $redemption_distance, $redemption_duration);

        return compact('price', 'distance', 'duration');
    }

    /**
     * @param $from_tariff
     * @param $to_tariff
     * @param $distance_data
     * @param $distance
     * @param $duration
     * @return float|int
     */
    private function fromToDistanceDurationSwitcher($from_tariff, $to_tariff, $distance_data, $distance, $duration): float|int
    {
        $comparator = isset($distance_data['a_b_distance'], $distance_data['b_c_distance']);

        switch ($from_tariff) {
            case $comparator && !$from_tariff['minimal_distance_value'] && !$from_tariff['minimal_duration_value']:
                $price = $distance * $from_tariff['price_km'];
                $price += $duration * $from_tariff['price_min'];
                $price += $comparator ? $distance_data['b_c_distance'] * $to_tariff['price_km'] : 0.0;
                $price += $comparator ? $distance_data['b_c_duration'] * $to_tariff['price_min'] : 0.0;

                break;
            case ConstTariffType::KM_AND_MIN()->equal(TariffPriceType::getTypeId($from_tariff['tariff_type_id'])):
                $price = $from_tariff['price_km'] * ($comparator ? $distance_data['a_b_distance'] : $distance);
                $price += $from_tariff['price_min'] * ($comparator ? $distance_data['a_b_duration'] : $duration);
                $comparator ? $price += $to_tariff['price_km'] * $distance_data['b_c_distance'] : null;
                $comparator ? $price += $to_tariff['price_min'] * $distance_data['b_c_duration'] : null;

                break;
            case ConstTariffType::MINUTE()->equal(TariffPriceType::getTypeId($from_tariff['tariff_type_id'])):
                $price = $from_tariff['price_min'] * ($comparator ? $distance_data['a_b_distance'] : $duration);
                $comparator ? $price += $to_tariff['price_min'] * $distance_data['b_c_distance'] : null;

                break;
            case ConstTariffType::KM()->equal(TariffPriceType::getTypeId($from_tariff['tariff_type_id'])):
                $price = $from_tariff['price_km'] * ($comparator ? $distance_data['a_b_distance'] : $distance);
                $comparator ? $price += $to_tariff['price_km'] * $distance_data['b_c_distance'] : null;

                break;

            default:
        }

        return $price ?? 0;
    }

    /**
     * @param $distance
     * @param $duration
     * @param $from_tariff
     * @param $to_tariff
     * @param $distance_data
     */
    protected function distanceDurationFilter(&$distance, &$duration, $from_tariff, $to_tariff, $distance_data): void
    {
        if ($from_tariff && $to_tariff) {
            $distance = $distance_data['a_b_distance'] + $distance_data['b_c_distance'];
            $duration = $distance_data['a_b_duration'] + $distance_data['b_c_duration'];
        } else {
            $duration = $distance_data['duration'];
        }
    }

    /**
     * @param $price
     * @param $from_data
     * @param $redemption_distance
     * @param $redemption_duration
     */
    protected function minDistanceDurationPrice(&$price, $from_data, $redemption_distance, $redemption_duration): void
    {
        if (!$redemption_distance && !$redemption_duration && $price <= $from_data['minimal_price']) {
            $price = $from_data['minimal_price'];
        }

        if ($redemption_distance || $redemption_duration) {
            $price = $from_data['minimal_price'] + $price;
        }
    }

    /**
     * @param $tariff_id
     * @return float|string
     */
    protected function calculateOptions($tariff_id): float|string
    {
        $price = 0.0;

        foreach ($this->demands as $demand) {
            $price += $this->carService->getOptionPrice($demand, $this->carClassId, $tariff_id) ?? 0.0;
        }

        return $price;
    }

    /**
     * @param  array  $tariffs
     * @param  array  $tariffs_data
     * @return float|int
     */
    protected function sitPriceCalc(array $tariffs, array $tariffs_data): float|int
    {
        $sit_price = 0;

        if (!($tariffs_data['from']['sitting_fee'] ?? false) && !($tariffs_data['to']['sitting_fee'] ?? false)) {
            return $sit_price;
        }

        if (!$tariffs['behind'] || $tariffs['behind'] === Tariff::BEHIND_NOTE) {
            $area = $this->areaContract->find($tariffs_data['from']['area_id'], ['area_id', 'area']);
            $behind = $this->geoService->pointInPolygon($area['area'], $this->fromCoordinates);

            if ($behind) {
                return 0;
            }

            if ($tariffs_data['from']['sit_type_id'] === ConstTariffType::DESTINATION()->getValue()) {
                $sit_price += $tariffs_data['from']['sit_fix_price'];
            } else {
                $result = $this->geoService->behindIntersectionCoordinateCord($this->fromCoordinates, $area['area']);
                $distance = $this->geoService->roadCalculation(['lat' => $result['cord'][0], 'lut' => $result['cord'][1]], $this->fromCoordinates);

                if ($tariffs_data['from']['sit_type_id'] === ConstTariffType::KM_AND_MIN()->getValue()) {
                    $sit_price += $tariffs_data['from']['sit_price_km'] * $distance['distance'];
                    $sit_price += $tariffs_data['from']['sit_price_minute'] * $distance['duration'];
                } elseif ($tariffs_data['from']['sit_type_id'] === ConstTariffType::KM()->getValue()) {
                    $sit_price += $tariffs_data['from']['sit_price_km'] * $distance['distance'];
                } else {
                    $sit_price += $tariffs_data['from']['sit_price_minute'] * $distance['duration'];
                }
            }
        }

        return $sit_price;
    }

    /**
     * @param $price
     * @param  int  $rounding
     */
    protected function roundingPrice(&$price, int $rounding): void
    {
        $price = (float)$price;

        switch ($rounding) {
            case ConsTariffRounding::UP_STAIRS()->equal($rounding):
                $price = ceil($price);
                break;
            case ConsTariffRounding::DAWN_WARDS()->equal($rounding):
                $price = floor($price);
                break;
            default:
        }
    }

    /**
     * @param $tariffs_data
     * @param $sitting_price
     * @param $option_price
     * @param $sitting_fee
     * @param $tariffs
     * @param $price
     * @param $price_distance
     * @return array
     */
    protected function filteredReturnData($tariffs_data, $sitting_price, $option_price, $sitting_fee, $tariffs, $price, $price_distance): array
    {
        if (ConstTariffType::DESTINATION()->equal(TariffPriceType::getTypeId($tariffs_data['from']['tariff_type_id']))) {
            return $this->getPrice(
                $price,
                $sitting_price,
                $option_price,
                $tariffs_data['from']['currency'],
                null,
                false,
                $sitting_fee,
                $tariffs['distance_data']['distance'],
                $tariffs['distance_data']['duration']
            );
        }

        return $this->getPrice(
            $price,
            $sitting_price,
            $option_price,
            $tariffs_data['from']['currency'],
            null,
            false,
            $sitting_fee,
            $price_distance['distance'],
            $price_distance['duration']
        );
    }

    /**
     * @param  float  $coin
     * @param  string  $currency
     * @param  float|null  $sitting_coin
     * @param  bool  $initial
     * @param  bool  $sitting_fee
     * @param  float|null  $distance
     * @param  float|null  $time
     * @return array
     * @noinspection PhpTooManyParametersInspection
     */
    protected function getPrice(
        float $coin,
        float $sitting_price,
        float $option_price,
        string $currency,
        float $sitting_coin = null,
        bool $initial = true,
        bool $sitting_fee = false,
        float $distance = null,
        float $time = null
    ): array {
        return compact('coin', 'sitting_price', 'option_price', 'currency', 'sitting_coin', 'distance', 'time', 'initial', 'sitting_fee');
    }

    /**
     * @param $tariffs
     * @return array
     * @throws Lexcept
     */
    #[ArrayShape([
        'coin' => 'mixed',
        'currency' => 'mixed'
    ])]
    protected function calculateRentPrice($tariffs): array
    {
        $tariff = $this->tariffContract
            ->whereHasMorph('current_tariff', [(new TariffRent())->getMap()], fn(Builder $query) => $query->where('hours', '=', $this->rentTime))
            ->with(['country' => fn(BelongsTo $query) => $query->select(['country_id', 'currency'])])
            ->find($tariffs['from_tariff'], ['country_id', 'minimal_price', 'tariffable_id', 'tariffable_type']);

        if (!$tariff) {
            throw new Lexcept('Not right data', 500);
        }

        return [
            'coin' => $tariff->minimal_price,
            'currency' => $tariff->country->currency
        ];
    }

    /**
     * @param  array  $tariff
     * @return array
     */
    protected function calculateInitialPrice(array $tariff): array
    {
        $tariff_data = $this->getTariffsData($tariff['from_tariff'], $tariff['to_tariff'], $tariff['behind']);

        $sit_price = $this->sitPriceCalc($tariff, $tariff_data);
        $option_price = $this->calculateOptions($tariff['from_tariff']);
        $price = $tariff_data['from']['minimal_price'];

        $price += $sit_price + $option_price;
        $is_sit = (bool)$sit_price;

        return $this->getPrice($price, $sit_price, $option_price, $tariff_data['from']['currency'], null, true, $is_sit);
    }
}
