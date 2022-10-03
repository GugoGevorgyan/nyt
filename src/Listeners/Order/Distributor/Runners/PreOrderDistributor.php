<?php

declare(strict_types=1);


namespace Src\Listeners\Order\Distributor\Runners;

use Illuminate\Support\Collection;
use ReflectionException;
use Src\Broadcasting\Broadcast\Driver\CommonOrderEvent;
use Src\Core\Complex\DetectOrderLocality;
use Src\Core\Contracts\Realizing;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstTariffLocality;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Jobs\OrderProcessing\PreOrderWatcher;
use Src\Listeners\Order\Distributor\Contracts\DecorateInterface;
use Src\Listeners\Order\Distributor\Contracts\RealizingInterface;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;

/**
 * Class PreOrderDistributor
 * @package Src\Listeners\Order
 */
class PreOrderDistributor implements DecorateInterface
{
    /**
     * @var RealizingInterface|null
     */
    protected Realizing|null $app = null;
    /**
     * @var int|null
     */
    protected ?int $objectNear = null;

    /**
     * @param  Realizing  $app
     * @return mixed
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

    /**
     * @param  mixed  ...$params
     * @throws ReflectionException
     */
    public function search(...$params): void
    {
        if ($this->app->orderContract->find($this->app->payload->order->order_id, ['order_id', 'status_id'])->status_id !== OrderStatus::ORDER_PENDING) {
            return;
        }

        $this->objectNear = DetectOrderLocality::complex($this->app->payload->order->order_id);

        $this->preOrder();
    }

    /**
     * Pre Order Starter
     */
    public function preOrder(): void
    {
        if (!$this->checkIsContinue()) {
            return;
        }

        $diff_minutes = $this->app->getPreorderDiff();
        $delay_ = $this->preOrderDistribution($diff_minutes);

        if ($delay_) {
            $this->app->preOrderContract->where('order_id', '=', $this->app->payload->order->order_id)->updateSet(['diff_minute' => $delay_]);

            PreOrderWatcher::dispatch($this->app->payload->order->order_id, $delay_, $this->objectNear)
                ->delay(now()->addMinutes($delay_))
                ->onQueue(ConstQueue::LONG()->getValue());
        } else {
            $this->app->preOrderContract->where('order_id', '=', $this->app->payload->order->order_id)->updateSet(['distribution_start' => now()]);
            $this->app->twisting();
        }
    }

    /**
     * @return bool
     */
    protected function checkIsContinue(): bool
    {
        if ($this->app->preOrderContract->where('order_id', '=', $this->app->payload->order->order_id)->whereHas('common',fn($query) => $query->where('accept', '=', true))->findFirst()) {
            return false;
        }

        if ($this->app->shippedContract->where('order_id', '=', $this->app->payload->order->order_id)->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * @param $diff
     * @return int|float|null
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function preOrderDistribution($diff): null|int|float // minutes
    {
        $meet_order = $this->app->orderContract->has('meet')->find($this->app->payload->order->order_id, ['order_id']);

        if ($meet_order && $diff >= 60 && $diff <= 90) {
            return null;
        }

        $has_drivers = $this->distDriverByDiffTime($diff);

        if ($has_drivers) {
            $minutes = $this->distDriverByDiffTimeAndDistance($diff);
            return $this->nearObjectCoefficient($minutes);
        }

        if ($diff < 60) {
            return 10;
        }

        return 5;
    }

    /**
     * @param $diff
     * @return bool
     */
    protected function distDriverByDiffTime($diff): bool
    {
        $has_drivers = true;

        if (240 >= $diff && 180 < $diff) {
            $has_drivers = (bool)$this->app->getPreOrderDrivers(95, 2.3, 15000, 15, [], false)->count();
        }
        if (180 >= $diff && 120 < $diff) {
            $has_drivers = (bool)$this->app->getPreOrderDrivers(90, 2, 10000, 15, [], false)->count();
        }
        if (120 >= $diff && 90 < $diff) {
            $has_drivers = (bool)$this->app->getPreOrderDrivers(60, 1.5, 8000, 15, [], true)->count();
        }
        if (90 >= $diff && 60 < $diff) {
            $has_drivers = (bool)$this->app->getPreOrderDrivers(50, 1, 7000, 15, [], true)->count();
        }
        if (60 >= $diff && 45 < $diff) {
            $has_drivers = (bool)$this->app->getPreOrderDrivers(50, 1, 6000, 15, [], true)->count();
        }
        if (240 < $diff) {
            $has_drivers = (bool)$this->app->getPreOrderDrivers(90, 2, 0, 15, [], false)->count();
        }

        return $has_drivers;
    }

    /**
     * @param $diff
     * @return int|void
     */
    protected function distDriverByDiffTimeAndDistance($diff)
    {
        if ($diff >= 45 && $diff <= 60) {
            $interval = now()->addMinutes($diff - 40);
            $drivers = $this->app->getPreOrderDrivers(60, 1, 7000, 10, [], true, $interval);
            if ($drivers) {
                $this->preorderPassDriversList($drivers, $this->app->payload->order);
                return 15/*minute*/ ;
            }
        }

        if ($diff > 60/*minute*/ && $diff <= 90/*minute*/) {
            $interval = now()->addMinutes($diff - 40);
            $drivers = $this->app->getPreOrderDrivers(100, 1.5, 9000, 10/*meter*/, [], false, $interval);
            if ($drivers) {
                $this->preorderPassDriversList($drivers, $this->app->payload->order);
                return 30/*minute*/ ;
            }
        }

        if ($diff > 90/*minute*/ && $diff <= 180) {
            $interval = now()->addMinutes($diff - 40);
            $drivers = $this->app->getPreOrderDrivers(150, 2, 10000, 10/*meter*/, [], false, $interval);
            if ($drivers) {
                $this->preorderPassDriversList($drivers, $this->app->payload->order);
                return 45/*minute*/ ;
            }
        }

        if ($diff > 180/*minute*/ && $diff < 240) {
            $interval = now()->addMinutes($diff - 40);
            $drivers = $this->app->getPreOrderDrivers(200, 2.5, 12000, 10/*meter*/, [], false, $interval);
            if ($drivers) {
                $this->preorderPassDriversList($drivers, $this->app->payload->order);
                return $diff - 60/*minute*/ ;
            }
        }

        if ($diff > 240) {
            $interval = now()->addMinutes($diff - 40);
            $drivers = $this->app->getPreOrderDrivers(200, 2.5, 20000, 10/*meter*/, [], false, $interval);
            if ($drivers) {
                $this->preorderPassDriversList($drivers, $this->app->payload->order);
                return $diff - 90/*minute*/ ;
            }
        }
    }

    /**
     * @param $drivers
     * @param $order
     */
    protected function preorderPassDriversList($drivers, $order): void
    {
        $ids = $drivers->map(fn($items) => $items->driver_id)->all();
        $this->app->commonContract->create( ['order_id' => $order->order_id, 'driver' => ['ids' => array_unique($ids)]]);

        foreach ($drivers as $driver) {
            $common_order = $this->app->orderService->createDriverCommonList($driver->driver_id, $order->order_id);
            $output = new PassOrderResource($common_order);
            CommonOrderEvent::broadcast($driver, $output);
        }
    }

    /** @noinspection PhpDuplicateSwitchCaseBodyInspection */
    /**
     * @param $minutes
     * @return float|int
     */
    protected function nearObjectCoefficient($minutes): float|int
    {
        if (!$this->objectNear) {
            return $minutes;
        }

        switch ($this->objectNear) {
            case ConstTariffLocality::city()->getValue():
                $minutes -= ($minutes * 4 / 100);
                break;
            case ConstTariffLocality::region()->getValue():
                $minutes += ($minutes * 4 / 100);
                break;
            case ConstTariffLocality::from_airport()->getValue():
                $minutes += ($minutes * 10 / 100);
                break;
            case ConstTariffLocality::to_airport()->getValue():
                $minutes += ($minutes * 5 / 100);
                break;
            case ConstTariffLocality::from_object()->getValue():
                $minutes += ($minutes * 5 / 100);
                break;
            case ConstTariffLocality::to_object()->getValue():
                $minutes += ($minutes * 3 / 100);
                break;
            default:
        }

        return $minutes;
    }

    /**
     *
     */
    public function annul(): void
    {
        $this->objectNear = null;
    }

    /**
     * @param  Collection  $driver
     * @param $distance
     * @param  int|null  $rating
     * @param  float|null  $assessment
     * @return Collection
     */
    protected function filterFindDriver(Collection $driver, $distance, int $rating = null, float $assessment = null): Collection
    {
        return $driver->flatMap(fn($item) => $item->where('distance', '<=', $distance));
    }
}
