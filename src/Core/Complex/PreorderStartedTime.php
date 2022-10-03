<?php

declare(strict_types=1);

namespace Src\Core\Complex;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use ReflectionException;
use Src\Core\Enums\ConstTariffLocality;
use Src\Core\Traits\Complex;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Order\OrderContract;
use Src\Services\Geocode\GeocodeServiceContract;

/**
 * @method static complex($orderId, $driverId, $preorderTime, $locationZone, $customerZone)
 * @property GeocodeServiceContract $geocodeService
 * @property DriverContract $driverContract
 * @property OrderContract $orderContract
 */
final class PreorderStartedTime
{
    use Complex;

    /**
     * @var Carbon
     */
    protected Carbon $justNow;
    /**
     * @var Collection|null
     */
    protected ?Collection $geoData = null;

    /**
     * @param $orderId
     * @param $driverId
     * @param $preorderTime
     * @param $locationZone
     * @param $customerZone
     */
    public function __construct(protected $orderId, protected $driverId, protected $preorderTime, protected $locationZone, protected $customerZone)
    {
        $this->justNow = now();
    }

    /**
     * @throws ReflectionException
     */
    public function handle(GeocodeServiceContract $geocodeService, DriverContract $driverContract, OrderContract $orderContract)
    {
        [$minutes, $near_objects] = $this->getAIdata();

        if (90 >= $minutes && $started_time = $this->closerOperation($minutes, $near_objects)) {
            return $started_time;
        }

        return $this->detailedLongProcessing($minutes, $near_objects);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    protected function getAIdata(): array
    {
        $minutes = $this->justNow->diffInMinutes(Carbon::parse($this->preorderTime, now()->timezone));
        $near_objects = DetectOrderLocality::complex($this->orderId);

        return [$minutes, $near_objects];
    }

    /**
     * @param  int  $minutes
     * @param $near_objects
     * @return bool|Carbon
     */
    protected function closerOperation(int $minutes, $near_objects): bool|Carbon
    {
        $order_cord = $this->orderContract->find($this->orderId, ['order_id', 'from_coordinates'])->from_coordinates;
        $driver_cord = $this->driverContract->getCordArray($this->driverId);
        $this->geoData = $this->geocodeService->getDistanceCoordinates($driver_cord, $order_cord);

        if (/*KM*/ 23 <= $this->geoData->get('distance')) {
            return $this->justNow;
        }

        if (/*MINUTE*/ 45 <= $this->geoData->get('duration')) {
            return $this->justNow;
        }

        if (50 < $minutes) {
            return $this->justNow->addMinutes(20);
        }

        if (40 < $minutes) {
            return $this->justNow->addMinutes(10);
        }

        return false;
    }

    /**
     * @param  int  $minutes
     * @param $near_objects
     * @return Carbon
     */
    protected function detailedLongProcessing(int $minutes, $near_objects): Carbon
    {
        $started_time = $this->justNow;

        if (240 < $minutes) {
            $started_time = $this->longProcessing($minutes, $near_objects);
        }

        if (240 >= $minutes && 210 < $minutes) {
            $started_time = match ($near_objects) {
                ConstTariffLocality::city()->getValue() => $this->justNow->addMinutes(190),
                ConstTariffLocality::region()->getValue() => $this->justNow->addMinutes(185),
                ConstTariffLocality::from_airport()->getValue() => $this->justNow->addMinutes(175),
                ConstTariffLocality::to_airport()->getValue() => $this->justNow->addMinutes(180),
                ConstTariffLocality::from_object()->getValue() => $this->justNow->addMinutes(186),
                ConstTariffLocality::to_object()->getValue() => $this->justNow->addMinutes(185),
                default => $minutes,
            };
        }

        if (210 > $minutes && 181 < $minutes) {
            $started_time = match ($near_objects) {
                ConstTariffLocality::city()->getValue() => $this->justNow->addMinutes(160),
                ConstTariffLocality::region()->getValue() => $this->justNow->addMinutes(150),
                ConstTariffLocality::from_airport()->getValue() => $this->justNow->addMinutes(140),
                ConstTariffLocality::to_airport()->getValue() => $this->justNow->addMinutes(145),
                ConstTariffLocality::from_object()->getValue() => $this->justNow->addMinutes(155),
                ConstTariffLocality::to_object()->getValue() => $this->justNow->addMinutes(151),
                default => $minutes,
            };
        }

        if (181 > $minutes && 150 < $minutes) {
            $started_time = match ($near_objects) {
                ConstTariffLocality::city()->getValue() => $this->justNow->addMinutes(125),
                ConstTariffLocality::region()->getValue() => $this->justNow->addMinutes(120),
                ConstTariffLocality::from_airport()->getValue() => $this->justNow->addMinutes(115),
                ConstTariffLocality::to_airport()->getValue() => $this->justNow->addMinutes(110),
                ConstTariffLocality::from_object()->getValue() => $this->justNow->addMinutes(121),
                ConstTariffLocality::to_object()->getValue() => $this->justNow->addMinutes(114),
                default => $minutes,
            };
        }

        if (151 > $minutes && 120 < $minutes) {
            $started_time = match ($near_objects) {
                ConstTariffLocality::city()->getValue() => $this->justNow->addMinutes(89),
                ConstTariffLocality::region()->getValue() => $this->justNow->addMinutes(85),
                ConstTariffLocality::from_airport()->getValue() => $this->justNow->addMinutes(70),
                ConstTariffLocality::to_airport()->getValue() => $this->justNow->addMinutes(60),
                ConstTariffLocality::from_object()->getValue() => $this->justNow->addMinutes(84),
                ConstTariffLocality::to_object()->getValue() => $this->justNow->addMinutes(80),
                default => $minutes,
            };
        }

        if (121 > $minutes && 90 < $minutes) {
            $started_time = match ($near_objects) {
                ConstTariffLocality::city()->getValue() => $this->justNow->addMinutes(65),
                ConstTariffLocality::region()->getValue() => $this->justNow->addMinutes(57),
                ConstTariffLocality::from_airport()->getValue() => $this->justNow->addMinutes(60),
                ConstTariffLocality::to_airport()->getValue() => $this->justNow->addMinutes(50),
                ConstTariffLocality::from_object()->getValue() => $this->justNow->addMinutes(55),
                ConstTariffLocality::to_object()->getValue() => $this->justNow->addMinutes(51),
                default => $minutes,
            };
        }

        return $started_time;
    }

    /**
     * @param $minutes
     * @param $near_objects
     * @return Carbon
     */
    protected function longProcessing($minutes, $near_objects): Carbon
    {
        return match ($near_objects) {
            ConstTariffLocality::city()->getValue() => $this->justNow->addMinutes($minutes - 50),
            ConstTariffLocality::region()->getValue() => $this->justNow->addMinutes($minutes - 65),
            ConstTariffLocality::from_airport()->getValue() => $this->justNow->addMinutes($minutes - 70),
            ConstTariffLocality::to_airport()->getValue() => $this->justNow->addMinutes($minutes - 60),
            ConstTariffLocality::from_object()->getValue() => $this->justNow->addMinutes($minutes - 30),
            ConstTariffLocality::to_object()->getValue() => $this->justNow->addMinutes($minutes - 45),
            default => $minutes,
        };
    }
}
