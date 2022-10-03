<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Calc\Calculate;

use JetBrains\PhpStorm\ArrayShape;
use Src\Broadcasting\Broadcast\Driver\RentTimeWarning;
use Src\Core\Contracts\Realizing;
use Src\Core\Enums\ConstRedis;
use Src\Core\Enums\ConstTariffType;
use Src\Listeners\Order\Calc\Contracts\CounterInterface;
use Src\Listeners\Order\Calc\Contracts\DecorateInterface;
use Src\Models\Order\OrderProcess;
use Src\Repositories\TariffRent\TariffRentContract;

/**
 * Calculate realtime rent car order by rent tariffs
 */
class RentCalc implements DecorateInterface
{
    /**
     * @var Realizing|null
     */
    protected Realizing|null $app = null;
    /**
     * @var OrderProcess|null
     */
    protected OrderProcess|null $process = null;
    /**
     * @var array|null
     */
    protected array|null $lastsCords = [];

    /**
     * @param  TariffRentContract  $rentContract
     */
    public function __construct(protected TariffRentContract $rentContract)
    {
    }

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
        $this->app = $app;

        return $this;
    }

    public function annul(): void
    {
        $this->app = null;
    }

    /**
     * @param  array  $last_cords
     * @param  OrderProcess  $process
     */
    public function calculate(array $last_cords, OrderProcess $process): void
    {
        $this->lastsCords = $last_cords;
        $this->process = $process;

        [$in_point, $diff_past] = $this->app->rentCriterion($process->travel_time);

        if ($in_point && 0 >= $diff_past) {
            $result = ['price' => 0, 'calculate_price' => 0, 'increment_price' => 0];

            $this->app->savePriceData($process, $result, $this->app->driver['current_order_stages']['started']);
        }

        $this->calcDistributor($in_point, $diff_past);
        $this->checkTimeEvent($diff_past);
    }

    /**
     * @param  bool  $in_point
     * @param $diff_past
     */
    private function calcDistributor(bool $in_point, $diff_past): void
    {
        $result = ['price' => 0, 'calculate_price' => 0, 'increment_price' => 0];

        if (!$in_point && 0 >= $diff_past) {
            $result = $this->nonPoint();
        }

        if ($in_point && 0 < $diff_past) {
            $result = $this->inPointTimeCompleted();
        }

        if (!$in_point && 0 < $diff_past) {
            $result = $this->nonPointTimeCompleted();
        }

        $this->app->savePriceData($this->process, $result, $this->app->driver['current_order_stages']['started']);
    }

    #[ArrayShape([
        'price' => 'float|int|string',
        'calculate_price' => 'float|int|string',
        'increment_price' => 'float|int|string'
    ])] protected function nonPoint(): array
    {
        $cost = $this->hasDestinationPoints();
        $cost += $this->hasAltRegional();

        return ['price' => $cost, 'calculate_price' => $cost, 'increment_price' => $cost];
    }

    /**
     * @return float|int|string
     */
    protected function hasDestinationPoints(): float|int|string
    {
        if (!$this->app->tariffs['initial_tariff']['rent_destinations']) {
            return 0;
        }

        $destination = 0;
        $red_key = ConstRedis::order_calc_data($this->app->driver->current_order->order_id);

        $design_points = $this->app->redis->hget($red_key, 'design_points');

        if ($design_points) {
            $design_points = igus($design_points);
        }

        foreach ($this->app->tariffs['initial_tariff']['rent_destinations'] as $dest) {
            $area = $this->app->areaContract->find($dest->{$dest->getKeyName()}, ['area_id', 'area']);

            if ($area) {
                $in_dest = $this->app->geoService->pointInPolygon($area->area, $this->app->cord);

                if ($in_dest) {
                    if ($design_points && \in_array($dest['tariff_destination_id'], $design_points, true)) {
                        break;
                    }

                    $this->app->redis->hset($red_key, 'design_points', igs(array_merge($design_points, [$dest->tariff_destination_id])));

                    $destination = $dest;
                    break;
                }
            }
        }

        return $destination->price ?? $destination;
    }

    /**
     * @return float|int
     */
    protected function hasAltRegional(): float|int
    {
        $price = 0;

        if ($this->app->tariffs['initial_tariff']['rent_current']['price_type_id'] === ConstTariffType::KM_AND_MIN()->getValue()) {
            $price = ($this->app->matrix['distance_meter'] / 1000 * $this->app->tariffs['initial_tariff']['rent_current']['price_km'])
                + $this->app->matrix['duration_second'] / 60 * $this->app->tariffs['initial_tariff']['rent_current']['price_min'];
        }

        if ($this->app->tariffs['initial_tariff']['rent_current']['price_type_id'] === ConstTariffType::KM()->getValue()) {
            $price = $this->app->matrix['distance_meter'] / 1000 * $this->app->tariffs['initial_tariff']['rent_current']['price_km'];
        }

        if ($this->app->tariffs['initial_tariff']['rent_current']['price_type_id'] === ConstTariffType::MINUTE()->getValue()) {
            $price = $this->app->matrix['duration_second'] / 60 * $this->app->tariffs['initial_tariff']['rent_current']['price_min'];
        }

        return $price;
    }

    #[ArrayShape([
        'price' => 'float|int|string',
        'calculate_price' => 'float|int|string',
        'increment_price' => 'float|int|string'
    ])] protected function inPointTimeCompleted(): array
    {
        $cost = $this->hasAltRegional();

        return ['price' => $cost, 'calculate_price' => $cost, 'increment_price' => $cost];
    }

    #[ArrayShape([
        'price' => 'float|int|string',
        'calculate_price' => 'float|int|string',
        'increment_price' => 'float|int|string'
    ])] protected function nonPointTimeCompleted(): array
    {
        $cost = $this->hasDestinationPoints();
        $cost += $this->hasAltRegional();

        return ['price' => $cost, 'calculate_price' => $cost, 'increment_price' => $cost];
    }

    /**
     * @param $diff
     */
    protected function checkTimeEvent($diff): void
    {
        if ($diff < -360) {
            return;
        }

        $has_send = $this->app->redis->hget(ConstRedis::order_calc_data($this->app->driver->current_order->order_id), 'rent_warning') ?? '';
        $rent_warning = igus($has_send);

        if ($diff > 0 && 0 !== $rent_warning) {
            $data = ['message' => trans('messages.rent_time_end')];
            RentTimeWarning::broadcast($this->app->driver, $data);
            $this->app->redis->hset(ConstRedis::order_calc_data($this->app->driver->current_order->order_id), 'rent_warning', igs(0));
        } elseif ($diff >= -360 && -1 !== $rent_warning) {
            $data = ['message' => trans('messages.rent_time_pre_end'), ['minute' => round(abs($diff) / 60)]];
            RentTimeWarning::broadcast($this->app->driver, $data);
            $this->app->redis->hset(ConstRedis::order_calc_data($this->app->driver->current_order->order_id), 'rent_warning', igs(-1));
        }
    }
}
