<?php

declare(strict_types=1);

namespace Src\Jobs\OrderCommons;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Broadcasting\Broadcast\Driver\PassLivePrice;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\OrderStageCord\OrderStageCordContract;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 *
 */
class WaitingPriceCalcQue implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var OrderStageCordContract
     */
    protected OrderStageCordContract $stageContract;
    /**
     * @var OrderShippedDriverContract
     */
    protected OrderShippedDriverContract $shippedContract;
    /**
     * @var OrderProcessContract
     */
    protected OrderProcessContract $processContract;
    /**
     * @var OrderContract
     */
    protected OrderContract $orderContract;
    /**
     * @var object
     */
    protected object $shippedTariff;
    /**
     * @var array{hash: string, order_id: int|string, now: Carbon}
     */
    protected array $data;

    /**
     * Create a new job instance.
     *
     * @param $hash
     * @param $order_id
     * @param $now
     */
    public function __construct($hash, $order_id, $now)
    {
        $this->data = compact('hash', 'order_id', 'now');
    }

    /**
     * Execute the job.
     *
     * @param  OrderStageCordContract  $stageContract
     * @param  OrderShippedDriverContract  $shippedDriverContract
     * @param  OrderProcessContract  $processContract
     * @param  OrderContract  $orderContract
     * @return void
     */
    public function handle(
        OrderStageCordContract $stageContract,
        OrderShippedDriverContract $shippedDriverContract,
        OrderProcessContract $processContract,
        OrderContract $orderContract
    ): void {
        $this->stageContract = $stageContract;
        $this->shippedContract = $shippedDriverContract;
        $this->processContract = $processContract;
        $this->orderContract = $orderContract;

        $this->getTariff();
        $this->waitingCalc();
    }

    /**
     * Calculate siting or cancel price
     */
    protected function getTariff(): void
    {
        $this->shippedTariff = $this->shippedContract
            ->where('on_way_hash', '=', $this->data['hash'])
            ->orWhere('in_order_hash', '=', $this->data['hash'])
            ->with([
                'initial_order_tariff' => fn(HasOneDeep $q_tariff) => $q_tariff
                    ->select([
                        'tariff_id',
                        'tariffable_id',
                        'tariffable_type',
                        'tariff_type_id',
                        'minimal_price',
                        'free_wait_minutes',
                        'paid_wait_minute',
                    ])
            ])
            ->findFirst(['order_shipped_driver_id', 'order_id', 'driver_id']);
    }

    /**
     *
     */
    protected function waitingCalc(): void
    {
        $stage = $this->stageContract
            ->where('order_id', '=', $this->data['order_id'])
            ->findFirst(['order_stage_cord_id', 'order_id', 'in_placed', 'started']);

        if (!$stage || !$stage->in_placed || !$stage->started) {
            return;
        }

        $diff = $stage->in_placed->diffInMinutes($stage->started);

        if ($this->shippedTariff->free_wait_minutes < $diff) {
            $waiting_price = $this->shippedTariff->paid_wait_minute * $diff;
        }

        $process_data = $this->createOnWayProcess($waiting_price ?? 0, $diff);

        if (!$process_data) {
            return;
        }

        $driver = $this->orderContract->getOrderedDriverData($this->data['order_id'], ['driver_id', 'car_id', 'current_franchise_id', 'phone']);

        if ($driver) {
            PassLivePrice::broadcast($driver, $process_data['total_price'], $process_data['distance_traveled'], $process_data['pause_time'], $process_data['travel_time']);
        }
    }


    /**
     * @param $waiting
     * @param $time
     * @return object|null
     */
    protected function createOnWayProcess($waiting, $time): ?array
    {
        $this->data['cord']['date'] = f_now();

        $order = $this->orderContract
            ->with([
                'on_way_roads' => fn(HasManyThrough $query) => $query->where('selected', '=', 1),
                'car_options' => fn(BelongsToJson $query) => $query->select(['car_option_id', 'price'])
            ])
            ->find($this->data['order_id']);

        if ($order && $order->on_way_roads->count() < 1) {
            return null;
        }

        $road = $order->on_way_roads->first();
        $road->update(['real_road' => [$this->data['cord']]]);


        $process = $this->processContract
            ->where('order_shipped_id', '=', $this->shippedTariff->order_shipped_driver_id)
            ->findFirst(['order_shipped_id', 'distance_traveled', 'travel_time', 'pause_time', 'total_price']);

        $this->processContract->updateOrCreate(
            ['order_shipped_id', '=', $this->shippedTariff->order_shipped_driver_id],
            [
                'order_shipped_id' => $this->shippedTariff->order_shipped_driver_id,
                'total_price' => ($process->total_price ?? 0 + $waiting),
                'waiting_price' => $waiting,
                'waiting_time' => $time,
            ]
        );

        if (!$process) {
            return null;
        }

        return [
            'order_shipped_id' => $process['order_shipped_id'],
            'distance_traveled' => $process['distance_traveled'],
            'travel_time' => $process['travel_time'],
            'pause_time' => $process['pause_time'],
            'total_price' => $process['total_price'] + $waiting,
        ];
    }
}
