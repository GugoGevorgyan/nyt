<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Calc\Calculate;

use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Contracts\Realizing;
use Src\Core\Enums\ConstTariffType;
use Src\Listeners\Order\Calc\Contracts\CounterInterface;
use Src\Listeners\Order\Calc\Contracts\DecorateInterface;
use Src\Models\Order\OrderProcess;
use Src\Models\Tariff\TariffPriceType;

/**
 * Calculate realtime order region cities tariff
 */
class RegionalCalc implements DecorateInterface
{
    /**
     * @var CounterInterface|null
     */
    protected Realizing|null $calc = null;

    /**
     * @param  Realizing  $app
     * @return static
     */
    public static function decorate(Realizing $app): self
    {
        return (clone app(self::class))->setApp($app);
    }

    /**
     * @param  Realizing  $app
     * @return $this
     */
    public function setApp(Realizing $app): static
    {
        $this->calc = $app;

        return $this;
    }

    /**
     * @return void
     */
    public function annul(): void
    {
        $this->calc = null;
    }

    /**
     * @param  array  $last_cords
     * @param  OrderProcess  $process
     * @return void
     */
    public function calculate(array $last_cords, OrderProcess $process): void
    {
        /** @noinspection NotOptimalIfConditionsInspection */
        if ($this->calc->currentTariffType() === ConstTariffType::KM_AND_MIN()->getValue() || $this->calc->currentTariffType() === ConstTariffType::MINUTE()->getValue()) {
            $result = $this->calculateTemporary($process);
        }

        /** @noinspection NotOptimalIfConditionsInspection */
        if ($this->calc->currentTariffType() === ConstTariffType::KM()->getValue()) {
            $result = $this->calculateDistances($process);
        }

        $this->calc->savePriceData($process, $result, $this->calc->driver['current_order_stages']['started']);
    }

    /**
     * @param $process
     * @return array
     */
    #[ArrayShape([
        'matrix' => 'array',
        'price' => 'float',
        'calculate_price' => 'float|int|mixed',
        'increment_price' => 'float|int|mixed',
        'minute' => 'bool',
        'km' => 'false'
    ])] protected function calculateTemporary($process): array
    {
        $redemption = $this->redemptionTravel($process);
        $current_price = $this->incrementTemporalPrice($process, $redemption);
        $total_view_price = $this->incrementTotalPrice($process, $redemption, $current_price);

        return [
            'matrix' => $this->calc->matrix,
            'price' => $total_view_price,
            'calculate_price' => $current_price['price_km'] + $current_price['price_min'],
            'increment_price' => $current_price['increment_price'],
            'minute' => true,
            'km' => false
        ];
    }

    /**
     * @param  OrderProcess  $process
     * @return array{distance: int|float, duration: int|float}
     */
    public function redemptionTravel(OrderProcess $process): array
    {
        $minimal_values = $this->calc->getMinimalDistDur();

        $redemption = ['distance' => false, 'duration' => false];
        $merge = $this->calc->tariffs['initial_tariff']['current_tariff']['merge_km_minute'] ?? false;

        if (!$minimal_values) {
            return $redemption;
        }

        if (0 !== $minimal_values['distance'] && $process->distance_traveled + $this->calc->matrix['distance_meter'] > $minimal_values['distance']) {
            $redemption['distance'] = true;
        } elseif (0 === $minimal_values['distance']) {
            $redemption['distance'] = 0;
        } else {
            $redemption['distance'] = false;
        }

        if (0 !== $minimal_values['duration'] && $process->travel_time + $this->calc->matrix['duration_second'] > $minimal_values['duration']) {
            $redemption['duration'] = true;
        } elseif (0 === $minimal_values['duration']) {
            $redemption['duration'] = 0;
        } else {
            $redemption['duration'] = false;
        }

        if ($merge && ($redemption['distance'] || $redemption['duration'])) {
            $redemption['duration'] = true;
            $redemption['distance'] = true;
        }

        return $redemption;
    }

    /**
     * @param  OrderProcess  $process
     * @param  array  $redemption
     * @return array|null
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function incrementTemporalPrice(OrderProcess $process, array $redemption): ?array
    {
        $price = 0.0;

        if ($this->calc->tariffs['initial_tariff']['current_tariff']['enable_speed_wait'] && $this->calc->matrix['km_hr'] > $this->calc->tariffs['initial_tariff']['current_tariff']['speed_wait_limit']) {
            $price = $this->calc->tariffs['initial_tariff']['current_tariff']['speed_wait_price_minute'] * ($this->calc->matrix['duration_second'] / 60);
        }

        if ($this->calc->currentTariffType() === TariffPriceType::getTypeId(ConstTariffType::KM_AND_MIN()->getValue())) {
            return $this->calcDistanceDuration($process, $price, $redemption);
        }

        if ($this->calc->currentTariffType() === TariffPriceType::getTypeId(ConstTariffType::MINUTE()->getValue())) {
            return $this->calcMinute($process, $price, $redemption);
        }

        return [];
    }

    /**
     * @param $process
     * @param $price
     * @param $redemption
     * @return array
     */
    #[ArrayShape([
        'price_km' => 'mixed',
        'price_min' => 'mixed',
        'increment_price' => 'float|int'
    ])]
    protected function calcDistanceDuration($process, $price, $redemption): array
    {
        [$diff_min_value_distance, $diff_min_value_duration] = $this->minValuesRedemptionIncrement($process);

        if (true === $redemption['distance']) {
            $increment_price_distance = $this->calc->tariffs['initial_tariff']['current_tariff']['price_km'] * ($diff_min_value_distance / 1000);
            $price_km = $increment_price_distance;
        } elseif (0 === $redemption['distance']) {
            $increment_price_distance = $this->calc->tariffs['initial_tariff']['current_tariff']['price_km'] * ($diff_min_value_distance / 1000);

            $price_km = $process->increment_price + $increment_price_distance >= $this->calc->getCoreCalculate($process)
                ? $increment_price_distance + $process->increment_price - $this->calc->getCoreCalculate($process)
                : 0.0;
        } else {
            $increment_price_distance = 0;
            $price_km = 0;
        }

        if (true === $redemption['duration']) {
            $increment_price_duration = $price + $this->calc->tariffs['initial_tariff']['current_tariff']['price_min'] * ($diff_min_value_duration / 60);
            $price_min = $increment_price_duration;
        } elseif (0 === $redemption['duration']) {
            $increment_price_duration = $this->calc->tariffs['initial_tariff']['current_tariff']['price_min'] * ($diff_min_value_duration / 60);

            $price_min = $process->increment_price + $increment_price_duration >= $this->calc->getCoreCalculate($process)
                ? $increment_price_duration + $process->increment_price - $this->calc->getCoreCalculate($process)
                : 0.0;
        } else {
            $increment_price_duration = 0;
            $price_min = 0;
        }

        return [
            'price_km' => $price_km ?? 0.0,
            'price_min' => $price_min ?? 0.0,
            'increment_price' => $increment_price_distance + $increment_price_duration
        ];
    }

    /**
     * @param  object  $process
     * @return array
     */
    protected function minValuesRedemptionIncrement(object $process): array
    {
        $min_values = $this->calc->getMinimalDistDur();

        $diff_min_value_distance = $this->calc->matrix['distance_meter'];
        $diff_min_value_duration = $this->calc->matrix['duration_second'];

        if ($min_values) {
            if ($process->distance_traveled + $this->calc->matrix['distance_meter'] > $min_values['distance']) {
                if ($process->distance_traveled > $min_values['distance']) {
                    $diff_min_value_distance = $this->calc->matrix['distance_meter'];
                } else {
                    $diff_min_value_distance = $process->distance_traveled + $this->calc->matrix['distance_meter'] - $min_values['distance'];
                }
            }

            if ($process->travel_time + $this->calc->matrix['duration_second'] > $min_values['duration']) {
                if ($process->travel_time > $min_values['duration']) {
                    $diff_min_value_duration = $this->calc->matrix['duration_second'];
                } else {
                    $diff_min_value_duration = $process->travel_time + $this->calc->matrix['duration_second'] - $min_values['duration'];
                }
            }
        }

        return [$diff_min_value_distance, $diff_min_value_duration];
    }

    /**
     * @param $process
     * @param $price
     * @param $redemption
     * @return array
     */
    #[ArrayShape([
        'price_km' => 'int',
        'price_min' => 'mixed',
        'increment_price' => 'float|int'
    ])]
    protected function calcMinute($process, $price, $redemption): array
    {
        $increment_price = 0.0;
        [$diff_min_value_distance, $diff_min_value_duration] = $this->minValuesRedemptionIncrement($process);

        if (true === $redemption['duration']) {
            $increment_price = $price + $this->calc->tariffs['initial_tariff']['current_tariff']['price_min'] * ($diff_min_value_duration / 60);
            $price_min = $increment_price;
        }

        if (0 === $redemption['duration']) {
            $increment_price = $this->calc->tariffs['initial_tariff']['current_tariff']['price_min'] * ($diff_min_value_duration / 60);
            $price_min = $process->increment_price >= $this->calc->getCoreCalculate($process) ? $increment_price + $process->increment_price - $this->calc->getCoreCalculate($process) : 0.0;
        }

        return ['price_km' => 0, 'price_min' => $price_min ?? 0, 'increment_price' => $increment_price ?? 0];
    }

    /**
     * @param  OrderProcess  $process
     * @param  array  $redemption
     * @param  array  $current_price
     * @return float
     */
    protected function incrementTotalPrice(OrderProcess $process, array $redemption, array $current_price): float
    {
        $total_view_price = 0.0;

        $to_cord = $this->calc->driver->current_order->to_coordinates;
        $change_initial_percent_ = $this->calc->tariffs['initial_tariff']['current_tariff']['change_initial_price_percent'] ?? null;

        $calculate_price = $this->calc->getCoreCalculate($process);
        $initial_price_ = $change_initial_percent_ ? $process->price + $process->price * $change_initial_percent_ / 100 : $process->price;
        $dif = $calculate_price >= $initial_price_ ? $calculate_price - $process->price : 0;

        if ($to_cord && ($this->calc->isDistDurTraveled($process, $this->calc->matrix) || 0 === $redemption['distance'] || 0 === $redemption['duration'])) {
            [$distance_price, $duration_price] = $this->incrementByToCord($current_price, $dif, $process);
            $this->mergeKmMinute($total_view_price, $redemption, $distance_price ?? 0.0, $duration_price ?? 0.0);

            return $total_view_price;
        }

        if (!$to_cord) {
            [$distance_price, $duration_price] = $this->incrementByCord($current_price, $process, $redemption);
            $this->mergeKmMinute($total_view_price, $redemption, $distance_price ?? 0.0, $duration_price ?? 0.0);

            return $total_view_price;
        }

        return $total_view_price;
    }

    /**
     * @param $current_price
     * @param $dif
     * @param $process
     * @return array
     */
    private function incrementByToCord($current_price, $dif, $process): array
    {
        if ($current_price['price_km'] && $dif) {
            $distance_price = $current_price['price_km'] + ($this->calc->getCoreTotal($process) === $process->price ? $dif : 0.0);
        }

        if ($current_price['price_min'] && $dif) {
            $duration_price = $current_price['price_min'] + ($this->calc->getCoreTotal($process) === $process->price ? $dif : 0.0);
        }

        return [$distance_price ?? 0, $duration_price ?? 0];
    }

    /**
     * @param $total_view_price
     * @param  array  $redemption
     * @param  float  $distance_price
     * @param  float  $duration_price
     */
    protected function mergeKmMinute(&$total_view_price, array $redemption, float $distance_price = 0.0, float $duration_price = 0.0): void
    {
        $merge_km_min = $this->calc->tariffs['initial_tariff']['current_tariff']['merge_km_minute'] ?? false;
        $price_type = $this->calc->currentTariffType();

        if ($merge_km_min) {
            if (false !== $redemption['distance'] || false !== $redemption['duration']) {
                $total_view_price += $this->kmMinuteMerge($price_type, $distance_price, $duration_price);
            }
        } else {
            $total_view_price += $this->kmMinuteNotMerge($redemption, $price_type, $distance_price, $duration_price);
        }
    }

    /**
     * @param $price_type
     * @param $distance_price
     * @param $duration_price
     * @return int|float
     */
    private function kmMinuteMerge($price_type, $distance_price, $duration_price): float|int
    {
        $total_view_price = 0;

        if (ConstTariffType::KM_AND_MIN()->getValue() === $price_type) {
            $total_view_price += $distance_price + $duration_price;
        }

        if (ConstTariffType::MINUTE()->getValue() === $price_type) {
            $total_view_price += $duration_price;
        }

        if (ConstTariffType::KM()->getValue() === $price_type) {
            $total_view_price += $distance_price;
        }

        return $total_view_price;
    }

    /**
     * @param $redemption
     * @param $price_type
     * @param $distance_price
     * @param $duration_price
     * @return int|float
     */
    private function kmMinuteNotMerge($redemption, $price_type, $distance_price, $duration_price): float|int
    {
        $total_view_price = 0;

        if ((false === $redemption['distance'] || true === $redemption['duration']) && (ConstTariffType::KM_AND_MIN()->getValue() === $price_type || ConstTariffType::MINUTE()->getValue() === $price_type)) {
            $total_view_price += $duration_price;
        }

        if ((true === $redemption['distance'] || false === $redemption['duration']) && (ConstTariffType::KM_AND_MIN()->getValue() === $price_type || ConstTariffType::KM()->getValue() === $price_type)) {
            $total_view_price += $distance_price;
        }

        if (true === $redemption['distance'] && true === $redemption['duration'] && ConstTariffType::KM_AND_MIN()->getValue() === $price_type) {
            $total_view_price += $distance_price + $duration_price;
        }

        return $total_view_price;
    }

    /**
     * @param $current_price
     * @param $process
     * @param $redemption
     * @return array
     */
    private function incrementByCord($current_price, $process, $redemption): array
    {
        if (true === $redemption['distance']) {
            $distance_price = $current_price['price_km'];
        }

        if (0 === $redemption['distance']) {
            $distance_price = $process->calculate_price + $current_price['price_km'] + $current_price['price_min'] >= $this->calc->getCoreTotal($process)
                ? $current_price['price_km']
                : 0.0;
        }

        if (true === $redemption['duration']) {
            $duration_price = $current_price['price_min'];
        }

        if (0 === $redemption['duration']) {
            $duration_price = $process->calculate_price + $current_price['price_km'] + $current_price['price_min'] >= $this->calc->getCoreTotal($process)
                ? $current_price['price_min']
                : 0.0;
        }

        return [$distance_price ?? 0, $duration_price ?? 0];
    }

    /**
     * @param $process
     * @return array|null
     */
    protected function calculateDistances($process): ?array
    {
        if ($this->calc->currentTariffType() === TariffPriceType::getTypeId(ConstTariffType::KM()->getValue())) {
            $redemption = $this->redemptionTravel($process);
            $current_price = $this->incrementDistancesPrice($process, $redemption);
            $total_view_price = $this->incrementTotalPrice($process, $redemption, $current_price);

            return [
                'matrix' => $this->calc->matrix,
                'price' => $total_view_price,
                'calculate_price' => $current_price['price_km'] + $current_price['price_min'],
                'increment_price' => $current_price['increment_price'],
                'minute' => false,
                'km' => true
            ];
        }

        return null;
    }

    /**
     * @param  OrderProcess  $process
     * @param  array  $redemption
     * @return array
     */
    #[ArrayShape([
        'price_km' => 'float|int|string',
        'price_min' => 'float',
        'increment_price' => 'float|int'
    ])]
    protected function incrementDistancesPrice(OrderProcess $process, array $redemption): array
    {
        $distance_price = 0.0;
        [$diff_min_value_distance, $diff_min_value_duration] = $this->minValuesRedemptionIncrement($process);

        if ($this->calc->tariffs['initial_tariff']['current_tariff']['enable_speed_wait'] && $this->calc->matrix['km_hr'] > $this->calc->tariffs['initial_tariff']['current_tariff']['speed_wait_limit']) {
            $distance_price += $this->calc->tariffs['initial_tariff']['current_tariff']['speed_wait_price_minute'] * ($this->calc->matrix['duration_second'] / 60);
        }

        if (true === $redemption['distance']) {
            $increment_price = $this->calc->tariffs['initial_tariff']['current_tariff']['price_km'] * ($diff_min_value_distance / 1000);
            $distance_price += $increment_price;
        }

        if (0 === $redemption['distance']) {
            $increment_price = $this->calc->tariffs['initial_tariff']['current_tariff']['price_km'] * ($diff_min_value_distance / 1000);
            $distance_price += $process->increment_price >= $this->calc->getCoreCalculate($process) ? $increment_price + $process->increment_price - $this->calc->getCoreCalculate($process) : 0.0;
        }

        return ['price_km' => $distance_price ?? 0.0, 'price_min' => 0.0, 'increment_price' => $increment_price ?? 0.0];
    }
}
