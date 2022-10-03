<?php

declare(strict_types=1);

namespace Src\Jobs\OrderProcessing;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Src\Broadcasting\Broadcast\Driver\OrderTimeOut;
use Src\Broadcasting\Broadcast\Driver\RegularOrder;
use Src\Core\Traits\Complex;
use Src\Exceptions\Order\OrderCanceledInSearchDriverException;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Models\Order\OrderShippedStatus;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\RatingPointService\RatingPointServiceContract;

/**
 * Class OrderShippedTikTak
 * @property OrderServiceContract orderService
 * @property DriverServiceContract driverService
 * @property RatingPointServiceContract ratingPointService
 * @property OrderShippedDriverContract orderShippedDriverContract
 * @package Src\Jobs
 */
class OrderShippedTikTak implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Complex;
    use Batchable;

    /**
     * @var Carbon
     */
    protected Carbon $now;

    /**
     * Create a new job instance.
     *
     * @param $driver
     * @param $shipped
     * @param  int  $tik_time
     */
    public function __construct(protected $driver, public $shipped, protected int $tik_time = 11)
    {
        $this->now = now();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws OrderCanceledInSearchDriverException
     */
    public function handle(): void
    {
        $this->inject(OrderServiceContract::class, DriverServiceContract::class, RatingPointServiceContract::class, OrderShippedDriverContract::class);

        RegularOrder::broadcast($this->driver, new PassOrderResource($this->shipped));

        while ($this->now->diffInSeconds(now()) <= $this->tik_time) {
            if ($this->orderService->orderHasCanceled($this->shipped['order_id'], true)
                || $this->driverService->driverHasAcceptOrder($this->driver->driver_id)
                || $this->driverService->driverIsRejectOrder($this->driver->driver_id)
            ) {
                break;
            }

            usleep(500000);
        }

        $this->shippedDeStatus();
    }

    /**
     *
     */
    public function shippedDeStatus(): void
    {
        $order_id = $this->shipped['order_id'];
        $driver_id = $this->driver['driver_id'];

        $shipped_order = $this->orderShippedDriverContract->getCurrentPendingShipped($order_id, $driver_id);

        if ($shipped_order) {
            $rating = $this->ratingPointService->setDriverRating($driver_id, $order_id, $shipped_order->estimated_rating->remove_patterns['ids']);

            $update_shipment = $this->orderShippedDriverContract->update($shipped_order->{$shipped_order->getKeyName()}, [
                'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_REJECTED),
                'current' => 0
            ]);
//            $update_shipment ? OrderTimeOut::broadcast($this->driver, $rating->get('rating')) : null;
        }
    }
}
