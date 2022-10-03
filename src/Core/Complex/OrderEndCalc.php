<?php

declare(strict_types=1);


namespace Src\Core\Complex;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use Src\Core\Enums\ConsTariffRounding;
use Src\Core\Enums\ConstTariffType;
use Src\Core\Traits\Complex;
use Src\Exceptions\Lexcept;
use Src\Models\Order\Order;
use Src\Models\Order\OrderInitialData;
use Src\Models\Order\OrderProcess;
use Src\Models\Tariff\Tariff;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\Destination\DestinationContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Repositories\TariffDestination\TariffDestinationContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\OrderEnd\OrderEndService;

/**
 * Class OrderEndCalc
 * @method static complex(Order $order, OrderProcess $process, OrderInitialData $initial, array $cords): Collection
 *
 * @return Collection
 *
 * @property  AreaContract areaContract
 * @property  TariffContract $tariffContract
 * @property  OrderEndService $orderEndService
 * @property  DestinationContract destinationContract
 * @property  OrderProcessContract $orderProcessContract
 *
 * @package Src\Core\Complex
 * @property  GeocodeServiceContract $geoService
 */
final class OrderEndCalc
{
    use Complex;

    /**
     * @var Tariff|null
     */
    protected ?Tariff $tariff = null;
    /**
     * @var bool
     */
    private bool $rounding = false;

    /**
     * OrderEndCalc constructor.
     * @param  Order  $order
     * @param  OrderProcess  $process
     * @param  OrderInitialData  $initial
     * @param  array  $cords
     */
    public function __construct(protected Order $order, protected OrderProcess $process, protected OrderInitialData $initial, protected array $cords)
    {
    }

    /**
     * Class Complex
     * @param  GeocodeServiceContract  $geoService
     * @param  TariffContract  $tariffContract
     * @param  TariffDestinationContract  $destinationContract
     * @param  AreaContract  $areaContract
     * @param  OrderEndService  $orderEndService
     * @param  OrderProcessContract  $orderProcessContract
     * @return Collection
     * @throws Lexcept
     * @package Src\Core
     * @method handle()
     */
    public function handle(
        GeocodeServiceContract $geoService,
        TariffContract $tariffContract,
        TariffDestinationContract $destinationContract,
        AreaContract $areaContract,
        OrderEndService $orderEndService,
        OrderProcessContract $orderProcessContract
    ): Collection {
        $this->getTariff();
        $this->diffPercent();

        if ($destination = $this->isDestinationTariff()) {
            $price = $destination;
        } elseif ($rent = $this->isRentTariff()) {
            $price = $rent;
        } elseif ($this->order->order_type_id === Order::ORDER_TYPE_CLIENT_BY_COMPANY || $this->order->order_type_id === Order::ORDER_TYPE_COMPANY_TO_CLIENT) {
            $price = $this->entityOrderEnd();
        } else {
            $price = $this->individualOrderEnd();
        }

        $this->priceRounding($price);
        return collect(['price' => $price, 'currency' => $this->initial->currency]);
    }

    /**
     * @return void
     */
    private function getTariff(): void
    {
        if ($this->tariff) {
            return;
        }

        $this->tariff = $this->tariffContract->getTariffWithArea($this->initial->initial_tariff_id, [
            'tariff_id',
            'tariff_type_id',
            'diff_percent',
            'tariffable_id',
            'tariffable_type',
            'rounding_price',
            'minimal_price'
        ]);
    }

    /**
     * @throws Lexcept
     */
    protected function diffPercent(): void
    {
        if (!$this->tariff || !$this->tariff->diff_percent) {
            return;
        }

        $process_data = $this->orderProcessContract
            ->whereHas('order', fn(Builder $query) => $query->where('orders.order_id', '=', $this->order->order_id))
            ->findFirst(['order_process_id', 'order_shipped_id', 'total_price', 'calculate_price', 'increment_price', 'distance_traveled', 'travel_time']);

        if (!$process_data) {
            return;
        }

        if (0 > $this->tariff->diff_percent) {
            $new_total = ($process_data->total_price) - ($process_data->total_price * $this->tariff->diff_percent) / 100;
            $new_calculate = ($process_data->calculate_price) - ($process_data->calculate_price * $this->tariff->diff_percent) / 100;
            $new_increment = ($process_data->increment_price) - ($process_data->increment_price * $this->tariff->diff_percent) / 100;

            if ($this->tariff->type_id === ConstTariffType::KM_AND_MIN()->getValue()) {
                $new_distance = ($process_data->distance_traveled) - (($process_data->distance_traveled) * $this->tariff->diff_percent / 2) / 100;
                $new_duration = ($process_data->travel_time) - (($process_data->travel_time) * $this->tariff->diff_percent / 2) / 100;
            } elseif ($this->tariff->type_id === ConstTariffType::KM()->getValue()) {
                $new_distance = ($process_data->distance_traveled) - (($process_data->distance_traveled) * $this->tariff->diff_percent) / 100;
            } elseif ($this->tariff->type_id === ConstTariffType::MINUTE()->getValue()) {
                $new_duration = ($process_data->travel_time) - (($process_data->travel_time) * $this->tariff->diff_percent) / 100;
            } else {
                throw new Lexcept('Error diff percent', 500);
            }
        } else {
            $new_total = ($process_data->total_price) + ($process_data->total_price * $this->tariff->diff_percent) / 100;
            $new_calculate = ($process_data->calculate_price) + ($process_data->calculate_price * $this->tariff->diff_percent) / 100;
            $new_increment = ($process_data->increment_price) + ($process_data->increment_price * $this->tariff->diff_percent) / 100;

            if ($this->tariff->type_id === ConstTariffType::KM_AND_MIN()->getValue()) {
                $new_distance = ($process_data->distance_traveled) + (($process_data->distance_traveled) * $this->tariff->diff_percent / 2) / 100;
                $new_duration = ($process_data->travel_time) + (($process_data->travel_time) * $this->tariff->diff_percent / 2) / 100;
            } elseif ($this->tariff->type_id === ConstTariffType::KM()->getValue()) {
                $new_distance = ($process_data->distance_traveled) + (($process_data->distance_traveled) * $this->tariff->diff_percent) / 100;
            } elseif ($this->tariff->type_id === ConstTariffType::MINUTE()->getValue()) {
                $new_duration = ($process_data->travel_time) + (($process_data->travel_time) * $this->tariff->diff_percent) / 100;
            } else {
                throw new Lexcept('Error diff percent', 500);
            }
        }

        $this->orderProcessContract
            ->whereHas('order', fn(Builder $query) => $query->where('orders.order_id', '=', $this->order->order_id))
            ->updateSet([
                'total_price' => $new_total,
                'calculate_price' => $new_calculate,
                'increment_price' => $new_increment,
                'distance_traveled' => $new_distance ?? $process_data->distance_traveled,
                'travel_time' => $new_duration ?? $process_data->travel_time
            ]);
    }

    /**
     * @return float|null
     */
    protected function isDestinationTariff(): ?float
    {
        if ($this->tariff->tariff_type_id !== ConstTariffType::DESTINATION()->getValue()) {
            return null;
        }

        $areas = $this->destinationContract
            ->with(['from_area', 'to_area'])
            ->where('tariff_id', '=', $this->initial->initial_tariff_id)
            ->findFirst();

        if (!$areas) {
            return null;
        }

        $from_in_polygon = $this->geoService->pointInPolygon($areas->from_area->area, $this->order->from_coordinates);
        $to_in_polygon = $this->geoService->pointInPolygon($areas->to_area->area, $this->order->to_coordinates);

        if ($from_in_polygon && $to_in_polygon) {
            return $areas->price;
        }

        return null;
    }

    /**
     * @return float|string
     */
    public function isRentTariff(): float|string
    {
        if ($this->tariff->tariff_type_id !== ConstTariffType::RENTAL()->getValue()) {
            return '';
        }

        if ($this->process->calculate_price === $this->tariff->minimal_price) {
            $this->rounding = false;
        }

        return $this->process->calculate_price ?: $this->tariff->minimal_price;
    }

    /**
     * @return float|null
     */
    protected function entityOrderEnd(): ?float
    {
        if ($this->process->calculate_price + $this->process->pause_price > $this->tariff->minimal_price) {
            $price = $this->process->calculate_price;
            $this->rounding = true;
        } else {
            $price = $this->tariff->minimal_price;
        }

        return (float)$price;
    }

    /**
     * @return float|string|null
     */
    protected function individualOrderEnd(): float|string|null
    {
        $price = 0.0;

        if (count($this->order->to_coordinates)) {
            $dist_diff = distance_cords($this->order->to_coordinates['lat'], $this->order->to_coordinates['lut'], $this->cords['end']['lat'],
                $this->cords['end']['lut'], 'm');

            if ($dist_diff <= config('nyt.end_distance_calculate')) {
                $price += $this->process->price;
            } else {
                $price += $this->process->calculate_price;
                $this->rounding = true;
            }
        } else {
            $price += $this->process->calculate_price;
            $this->rounding = true;
        }

        return $price;
    }

    /**
     * @param $price
     */
    protected function priceRounding(&$price): void
    {
        if ($price > $this->tariff->minimal_price && $this->rounding) {
            switch ($this->tariff->tariff_type_id) {
                case ConstTariffType::KM_AND_MIN()->getValue():
                    $price += $this->minuteDiffBalance($this->tariff);
                    $price += $this->distanceDiffBalance($this->tariff);
                    break;
                case ConstTariffType::MINUTE()->getValue():
                    $price += $this->minuteDiffBalance($this->tariff);
                    break;
                case ConstTariffType::KM()->getValue():
                    $price += $this->distanceDiffBalance($this->tariff);
                    break;
                default:
            }

            $price += $this->process->pause_price;
        } else {
            $price += $this->process->pause_price + $this->process->options_price + $this->process->waiting_price + $this->hasSittingPrices();
        }

        switch ($this->tariff->rounding_price) {
            case ConsTariffRounding::UP_STAIRS()->getValue():
                $price = ceil($price);
                break;
            case ConsTariffRounding::DAWN_WARDS()->getValue():
                $price = floor($price);
                break;
            default:
        }
    }

    /**
     * @param $tariff
     * @return float|int
     */
    protected function minuteDiffBalance($tariff): float|int
    {
        $travel_time = $this->process->travel_time - ($this->process->pause_time ? ($this->process->pause_time - 1) : 0);

        return (ceil($travel_time / 60) * 60 - $travel_time) * ($tariff->current_tariff->price_min / 60);
    }

    /**
     * @param $tariff
     * @return float|int
     */
    #[Pure] protected function distanceDiffBalance($tariff): float|int
    {
        $price_percent_distance = (round_d($this->process->distance_traveled)) - ($this->process->distance_traveled / 1000);

        return $price_percent_distance * $tariff->current_tariff->price_km;
    }

    /**
     * @return float|int|string
     */
    protected function hasSittingPrices(): float|int|string
    {
        if (!$this->process->sitting_price || !$this->tariff->area['area']) {
            return 0;
        }

        if ($this->geoService->pointInPolygon($this->tariff->area['area'], $this->cords['start'])) {
            return 0;
        }

        return $this->process->sitting_price;
    }
}
