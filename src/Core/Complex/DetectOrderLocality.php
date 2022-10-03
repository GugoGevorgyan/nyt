<?php

declare(strict_types=1);

namespace Src\Core\Complex;

use Src\Core\Enums\ConstTariffLocality;
use Src\Core\Traits\Complex;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Repositories\RailwayStation\RailwayStationContract;

/**
 * @property  InitialOrderDataContract $initialOrderDataContract,
 * @property AreaContract $areaContract,
 * @property AirportContract $airportContract,
 * @property RailwayStationContract $railwayStationContract
 * @method static complex(int $order_id)
 */
final class DetectOrderLocality
{
    use Complex;

    /**
     * @param  int  $orderId
     */
    public function __construct(protected int $orderId)
    {
    }

    /**
     * @param  InitialOrderDataContract  $initialOrderDataContract
     * @param  AreaContract  $areaContract
     * @param  AirportContract  $airportContract
     * @param  RailwayStationContract  $railwayStationContract
     * @return int|null
     */
    public function handle(
        InitialOrderDataContract $initialOrderDataContract,
        AreaContract $areaContract,
        AirportContract $airportContract,
        RailwayStationContract $railwayStationContract
    ): ?int {
        return $this->isRegional();
    }

    /**
     * @return int|null
     */
    protected function isRegional(): ?int
    {
        $initial = $this->initialOrderDataContract
            ->where('order_id', '=', $this->orderId)
            ->with(['order' => fn($query) => $query->select(['order_id', 'from_coordinates', 'to_coordinates'])])
            ->findFirst(['order_id', 'region_id', 'city_id', 'initial_tariff_id']);

        if (!$initial) {
            return null;
        }

        if ($initial->city_id && $initial->region_id) {
            return ConstTariffLocality::city()->getValue();
        }

        if (!$initial->city_id && $initial->region_id && $object = $this->detectObject($initial->order->from_coordinates, $initial->order->to_coordinates)) {
            return $object;
        }

        return ConstTariffLocality::region()->getValue();
    }

    /**
     * @param  array  $from_cord
     * @param  array  $to_cord
     * @return int|null
     */
    protected function detectObject(array $from_cord, array $to_cord = []): ?int
    {
        $airport = $this->airport($from_cord, $to_cord);
        $railway = $this->railway($from_cord, $to_cord);

        if ($airport) {
            return $airport;
        }

        if ($railway) {
            return $railway;
        }

        return null;
    }

    /**
     * @param  array  $from_cord
     * @param  array  $to_cord
     * @return int|null
     */
    protected function airport(array $from_cord, array $to_cord = []): ?int
    {
        $distance = 'distance < 2.0';

        $from_airports = $this->airportContract
            ->cordDistance($from_cord['lat'], $from_cord['lut'])
            ->havingRaw($distance)
            ->findAll(['area', 'distance']);

        if ($from_airports) {
            return ConstTariffLocality::from_airport()->getValue();
        }

        if ($to_cord) {
            $to_airports = $this->airportContract
                ->cordDistance($to_cord['lat'], $to_cord['lut'])
                ->havingRaw($distance)
                ->findAll(['area', 'distance']);

            if ($to_airports) {
                return ConstTariffLocality::to_airport()->getValue();
            }
        }

        return null;
    }

    /**
     * @param  array  $from_cord
     * @param  array  $to_cord
     * @return int|null
     */
    protected function railway(array $from_cord, array $to_cord = []): ?int
    {
        $distance = 'distance < 0.7';

        $from_railway = $this->railwayStationContract
            ->cordDistance($from_cord['lat'], $from_cord['lut'])
            ->havingRaw($distance)
            ->findAll(['area', 'distance']);

        if ($from_railway) {
            return ConstTariffLocality::from_object()->getValue();
        }

        if ($to_cord) {
            $to_railway = $this->railwayStationContract
                ->cordDistance($to_cord['lat'], $to_cord['lut'])
                ->havingRaw($distance)
                ->findAll(['area', 'distance']);

            if ($to_railway) {
                return ConstTariffLocality::to_object()->getValue();
            }
        }

        return null;
    }
}
