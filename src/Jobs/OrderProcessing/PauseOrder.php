<?php

declare(strict_types=1);

namespace Src\Jobs\OrderProcessing;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use ReflectionException;
use Src\Broadcasting\Broadcast\Driver\PassLivePrice;
use Src\Core\Complex\OrderPauseCalculate;
use Src\Core\Enums\ConstRedis;
use Src\Models\Driver\Driver;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderStageCord\OrderStageCordContract;
use Src\Repositories\Tariff\TariffContract;

/**
 * Class PauseOrder
 * @package Src\Jobs
 */
class PauseOrder implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var int
     */
    public int $timeout = 9600;
    /**
     * @var OrderContract
     */
    protected OrderContract $orderContract;
    /**
     * @var OrderStageCordContract
     */
    protected OrderStageCordContract $stageContract;
    /**
     * @var DriverContract
     */
    protected DriverContract $driverContract;
    /**
     * @var TariffContract
     */
    protected TariffContract $tariffContract;

    /**
     * Create a new job instance.
     *
     * @param  Driver  $driver
     * @param  string  $pauseHash
     */
    public function __construct(protected Driver $driver, protected string $pauseHash)
    {
    }

    /**
     * Execute the job.
     *
     * @param  OrderContract  $orderContract
     * @param  DriverContract  $driverContract
     * @param  OrderStageCordContract  $stageCordContract
     * @param  TariffContract  $tariffContract
     * @return void
     * @throws ReflectionException
     */
    public function handle(
        OrderContract $orderContract,
        DriverContract $driverContract,
        OrderStageCordContract $stageCordContract,
        TariffContract $tariffContract
    ): void {
        $this->orderContract = $orderContract;
        $this->driverContract = $driverContract;
        $this->stageContract = $stageCordContract;
        $this->tariffContract = $tariffContract;
        $this->dataMaster();
    }

    /**
     * @throws ReflectionException
     */
    protected function dataMaster(): void
    {
        $this->pauseTimeOut($this->driver->current_order->order_id, $this->driver, now());
    }

    /**
     * @param $order_id
     * @param  Driver  $driver
     * @param  Carbon  $start
     * @throws ReflectionException
     */
    protected function pauseTimeOut($order_id, Driver $driver, Carbon $start): void
    {
        $redis = Redis::connection('app');

        $tariff = $this->tariffContract
            ->with(['current_tariff' => fn($tariff) => $tariff->select(['*'])])
            ->find($driver->initial_order_data->initial_tariff_id);

        while ($this->orderContract->find($order_id, ['order_id', 'status_id'])->status_id === OrderStatus::ORDER_PAUSED) {
            $redis->hincrby(ConstRedis::order_pause_time($order_id), 'time', 1);

            if ($start->diffInSeconds(now()) > config('nyt.price_send_interval')) {
                $prices = OrderPauseCalculate::complex($driver, $driver->order_client, $driver->current_order, $tariff, $start);

                PassLivePrice::broadcast(
                    $driver,
                    $prices['price'],
                    $prices['distance'],
                    $redis->hmget(ConstRedis::order_pause_time($order_id), ['time'])[0],
                    $prices['travel_time']
                );

                $start = now();
            }

            sleep(1);
        }
    }
}
