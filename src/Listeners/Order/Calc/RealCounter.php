<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Calc;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Facades\Redis;
use JetBrains\PhpStorm\ArrayShape;
use Src\Broadcasting\Broadcast\Driver\PassLivePrice;
use Src\Core\Enums\ConstRedis;
use Src\Events\Driver\CoordinatesUpdateEvent;
use Src\Listeners\Order\Calc\Calculate\DestinationCalc;
use Src\Listeners\Order\Calc\Calculate\RegionalCalc;
use Src\Listeners\Order\Calc\Calculate\RentCalc;
use Src\Listeners\Order\Calc\Contracts\CounterInterface;
use Src\Listeners\Order\Calc\Utils\CalcUtils;
use Src\Models\Order\OrderInProcessRoad;
use Src\Models\Order\OrderOnWayRoad;
use Src\Models\Order\OrderProcess;
use Src\Models\Tariff\TariffDestination;
use Src\Models\Tariff\TariffPriceType;
use Src\Models\Tariff\TariffRegionCity;
use Src\Models\Tariff\TariffRent;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Repositories\TariffRent\TariffRentContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;

use function array_slice;

/**
 *
 */
class RealCounter extends CalcUtils implements CounterInterface
{
    /**
     * @var Connection|Redis|null
     */
    public Redis|Connection|null $redis = null;

    /**
     * Create the event listener.
     *
     * @param  DriverContract  $driverContract
     * @param  OrderServiceContract  $orderService
     * @param  ClientContract  $clientContract
     * @param  ClientServiceContract  $clientService
     * @param  TariffContract  $tariffContract
     * @param  DriverServiceContract  $driverService
     * @param  GeocodeServiceContract  $geoService
     * @param  AreaContract  $areaContract
     * @param  OrderProcessContract  $processContract
     * @param  TariffRentContract  $rentContract
     */
    public function __construct(
        public GeocodeServiceContract $geoService,
        public AreaContract $areaContract,
        protected DriverContract $driverContract,
        protected OrderServiceContract $orderService,
        protected ClientContract $clientContract,
        protected ClientServiceContract $clientService,
        protected TariffContract $tariffContract,
        protected DriverServiceContract $driverService,
        protected OrderProcessContract $processContract,
        protected TariffRentContract $rentContract,
    ) {
        $this->redis = ($this->redis instanceof PhpRedisConnection || $this->redis instanceof PredisConnection) ? $this->redis : redis();
    }

    /**
     * Handle the event.
     *
     * @param  CoordinatesUpdateEvent  $event
     * @return void
     */
    public function handle(CoordinatesUpdateEvent $event): void
    {
        $this->injectData($event);

        $driver = $this->getDriverInOrderData();

        if (!$driver) {
            return;
        }

        $process_road = $this->getProcessRoad();

        if ($driver->current_order && $process_road) {
            $this->getInitialTariffs($process_road);
            $this->inProcess($process_road);
        }

        $this->annul();
    }

    /**
     * @param  OrderOnWayRoad|OrderInProcessRoad|Model  $road
     */
    protected function inProcess(OrderOnWayRoad|OrderInProcessRoad|Model $road): void
    {
        $process = $this->getProcess();

        if (!$process) {
            return;
        }

        $last_cords = array_values(array_slice($road->real_road, -2, 2, true));
        $this->matrix = $this->matrix($last_cords, $process);

        $this->runner($last_cords, $process);
        $this->sendData();
    }

    /**
     * @param  mixed  ...$params
     */
    public function runner(...$params): void
    {
        $this->getTariff();

        if (!$this->tariffs) {
            return;
        }

        if ($this->tariffs['initial_tariff']['tariffable_type'] === (new TariffDestination())->getMap()) {
            $destination = DestinationCalc::decorate($this);
            $destination->calculate($params[0], $params[1]);
            $destination->annul();

            return;
        }

        if ($this->tariffs['initial_tariff']['tariffable_type'] === (new TariffRegionCity())->getMap()) {
            $regional = RegionalCalc::decorate($this);
            $regional->calculate($params[0], $params[1]);
            $regional->annul();

            return;
        }

        if ($this->tariffs['initial_tariff']['tariffable_type'] === (new TariffRent())->getMap()) {
            $rent = RentCalc::decorate($this);
            $rent->calculate($params[0], $params[1]);
            $rent->annul();
        }
    }

    protected function sendData(): void
    {
        if (($this->passedData['passed'] ?? false) && $this->driver) {
            PassLivePrice::broadcast(
                $this->driver,
                $this->passedData['total_price'],
                $this->passedData['distance_traveled'],
                $this->passedData['pause_time'],
                $this->passedData['travel_time']
            );
        }
    }

    /**
     * @param  OrderProcess  $process
     * @param  array  $result
     * @param  Carbon  $order_started
     * @return void
     */
    public function savePriceData(OrderProcess $process, array $result, Carbon $order_started): void
    {
        $passed = $this->passed($process, $order_started);

        $this->processContract->update($process->{$process->getKeyName()}, [
            'speed' => (int)$this->matrix['km_hr'],
            'price_passed' => $passed ?: $process->price_passed,
            'travel_time' => $process->travel_time + ($this->matrix['duration_second'] ?? 0),
            'distance_traveled' => $process->distance_traveled + ($this->matrix['distance_meter'] ?? 0),
            'total_price' => $process->total_price + ($result['price'] ?? 0),
            'calculate_price' => $process->calculate_price + ($result['calculate_price'] ?? 0),
            'increment_price' => $process->increment_price + ($result['increment_price'] ?? 0),
        ]);

        $this->passedData = [
            'passed' => $passed,
            'pause_time' => $process->pause_time ?? 0,
            'travel_time' => round_t($process->travel_time + $this->matrix['duration_second']) ?? 0.0,
            'distance_traveled' => round_d($process->distance_traveled + $this->matrix['distance_meter']) ?? 0.0,
            'total_price' => $process->total_price + ($result['price'] ?? 0),
        ];
    }

    /**
     * @param  OrderProcess  $process
     * @param  array  $matrix
     * @return bool
     */
    public function isDistDurTraveled(OrderProcess $process, array $matrix): bool
    {
        return
            $this->driver['order_in_process_road']['distance'] <= round(($process->distance_traveled + $matrix['distance_meter']) / 1000, 1)
            || ($this->driver['order_in_process_road']['duration'] <= (int)round(($process->travel_time + $matrix['duration_second']) / 60));
    }

    /**
     * @return array{distance: string|int|float, duration: string|int}
     */
    #[ArrayShape([
        'distance' => 'int|float',
        'duration' => 'int|string',
    ])] public function getMinimalDistDur(): array
    {
        $has_minimals = $this->redis->hmget(ConstRedis::order_calc_data($this->driver->current_order->order_id), ['min_distance', 'min_duration']);

        if (!$has_minimals) {
            return [];
        }

        return ['distance' => $has_minimals[0] ? $has_minimals[0] * 1000 : 0, 'duration' => $has_minimals[1] ? $has_minimals[1] * 60 : 0];
    }

    /**
     * @inher
     */
    public function currentTariffType(): string|int
    {
        return TariffPriceType::getTypeId($this->tariffs['initial_tariff']['current_tariff']['price_type_id']);
    }

    /**
     * @inheritdoc
     */
    public function getCoreCalculate($process): float|int|string
    {
        return $process->calculate_price - ($process->sitting_price + $process->options_price);
    }

    /**
     * @inheritDoc
     */
    public function getCoreTotal($process): float|int|string
    {
        return $process->total_price - ($process->pause_price + $process->waiting_price);
    }

    /**
     * @param $travel_time
     * @return array
     */
    public function rentCriterion($travel_time = null): array
    {
        $area = $this->areaContract->find($this->tariffs['initial_tariff']['current_tariff']['area_id'], ['area_id', 'area']);

        $in_point = $this->geoService->pointInPolygon($area->area, $this->cord);
        $dif_past = $travel_time ? ($travel_time + $this->matrix['duration_second']) - ($this->tariffs['initial_tariff']['current_tariff']['hours'] * 3600) : 0;

        return [$in_point, $dif_past];
    }
}
