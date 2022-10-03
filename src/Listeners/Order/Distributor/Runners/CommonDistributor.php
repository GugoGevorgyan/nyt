<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Distributor\Runners;

use Spatie\Async\Pool;
use Src\Broadcasting\Broadcast\Driver\CommonOrderEvent;
use Src\Core\Contracts\Realizing;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Listeners\Order\Distributor\Contracts\CommonContract;
use Src\Listeners\Order\Distributor\Contracts\DecorateInterface;

/**
 *
 */
class CommonDistributor implements CommonContract, DecorateInterface
{
    /**
     * @var Realizing|null
     */
    protected Realizing|null $app = null;

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
     */
    public function search(...$params): void
    {
        $this->commonList();
    }

    /**
     *
     */
    public function commonList(): void
    {
        $drivers = $this->app->getDrivers(80, 1.5, 7000, null, 10);

        $pool = Pool::create();
        foreach ($drivers as $driver) {
            $pool
                ->add(fn() => $this->app->orderService->createDriverCommonList($driver->driver_id, $this->app->payload->order->order_id))
                ->then(fn($output) => $output ? CommonOrderEvent::broadcast($driver, new PassOrderResource($output)) : null);
        }
        $pool->wait();

        $passed = now();
        $ids = $drivers->map(fn($items) => $items->driver_id)->all();

        $common = $this->app->commonContract->create([
            'order_id' => $this->app->payload->order->order_id,
            'driver' => ['ids' => $ids],
            'emergency' => $ids ? 0 : 1
        ]);

        $this->tikTakCommon($common, $drivers, $passed);
    }

    /**
     * @param $common
     * @param $drivers
     * @param $passed
     */
    public function tikTakCommon($common, $drivers, $passed): void
    {
        while ($this->app->payload->timer->diffInSeconds(now()) < config('nyt.taxi_search_time')) {
            if ($this->app->orderService->orderHasCanceled($this->app->payload->order->order_id, true)) {
                if (!$common->emergency) {
                    foreach ($drivers as $driver) {
                        CommonOrderEvent::broadcast($driver, new PassOrderResource(['order_id' => $common->order_id]), 'delete');
                    }
                }

                $this->app->driverNotFound();
                break;
            }

            if (!$common->emergency && $passed->diffInSeconds(now()) >= config('nyt.common_order_timeout')) {
                $common->update(['emergency' => true]);

                foreach ($drivers as $driver) {
                    CommonOrderEvent::broadcast($driver, new PassOrderResource(['order_id' => $common->order_id]), 'delete');
                }
            }

            usleep(400000);
        }

        if (!$this->app->shippedContract->isOrderPassed($this->app->payload->order->order_id)) {
            $this->app->canceledOrderContract->create(['order_id' => $this->app->payload->order->order_id]);
            $this->app->driverNotFound();
        }
    }

    /**
     *
     */
    public function annul(): void
    {
        // TODO: Implement annul() method.
    }
}
