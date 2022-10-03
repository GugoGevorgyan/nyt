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
use Src\Broadcasting\Broadcast\Client\ClientPassOrderPrice;
use Src\Broadcasting\Broadcast\Driver\PassLivePrice;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Services\Client\ClientServiceContract;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class CancelPriceCalcQue
 * @package Src\Jobs
 */
class CancelPriceCalcQue implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var ClientServiceContract
     */
    protected ClientServiceContract $clientService;
    /**
     * @var OrderShippedDriverContract
     */
    protected OrderShippedDriverContract $shippedContract;
    /**
     * @var OrderContract
     */
    protected OrderContract $orderContract;
    /**
     * @var OrderProcessContract
     */
    protected OrderProcessContract $processContract;
    /**
     * @var array{hash: string, order_id: int|string, now: Carbon, cord: array, duration: int|float, distance: int|float}
     */
    protected array $data;

    /**
     * Create a new job instance.
     *
     * @param $hash
     * @param $order_id
     * @param $now
     * @param $cord
     * @param $duration
     * @param $distance
     */
    public function __construct($hash, $order_id, $now, $cord, $duration, $distance)
    {
        $this->data = compact('hash', 'order_id', 'now', 'cord', 'duration', 'distance');
    }

    /**
     * Execute the job.
     *
     * @param  OrderShippedDriverContract  $shipmentDriverContract
     * @param  ClientServiceContract  $clientServiceContract
     * @param  OrderContract  $orderContract
     * @param  OrderProcessContract  $processContract
     * @return void
     */
    public function handle(
        OrderShippedDriverContract $shipmentDriverContract,
        ClientServiceContract $clientServiceContract,
        OrderContract $orderContract,
        OrderProcessContract $processContract
    ): void {
        $this->shippedContract = $shipmentDriverContract;
        $this->orderContract = $orderContract;
        $this->clientService = $clientServiceContract;
        $this->processContract = $processContract;

        $this->cancelPrice();
    }

    /**
     * Calculate siting or cancel price
     */
    protected function cancelPrice(): void
    {
        $shipped = $this->shippedContract
            ->where('on_way_hash', '=', $this->data['hash'])
            ->orWhere('in_order_hash', '=', $this->data['hash'])
            ->with([
                'initial_order_tariff' => fn(HasOneDeep $q_tariff) => $q_tariff->select([
                    'tariff_id',
                    'tariffable_id',
                    'tariffable_type',
                    'tariff_type_id',
                    'minimal_price',
                    'free_wait_minutes',
                    'paid_wait_minute',
                ]),
                'initial_order' => fn($query) => $query->select([
                    'order_initial_data.order_initial_data_id',
                    'order_initial_data.order_id',
                    'order_initial_data.price',
                    'order_initial_data.option_price',
                    'order_initial_data.sitting_price',
                ])
            ])
            ->findFirst(['order_shipped_driver_id', 'order_id', 'driver_id']);

        if (!$shipped && !$shipped->initial_order_tariff) {
            return;
        }

        $this->hasInitialCalc($shipped);
    }

    /**
     * @param  object  $shipped_initial_data
     */
    protected function hasInitialCalc(object $shipped_initial_data): void
    {
        $initial_price =
            ($shipped_initial_data->initial_order->price - $shipped_initial_data->initial_order->option_price - $shipped_initial_data->initial_order->sitting_price)
            ?? $shipped_initial_data->initial_order_tariff->minimal_price;

        $distance = $this->data['distance'];
        $duration = $this->data['duration'];

        $sitting_price = $shipped_initial_data->initial_order->sitting_price;
        $option_price = $shipped_initial_data->initial_order->option_price;

        $cancel_price = $this->calcSitPrice($shipped_initial_data);

        $price = $this->createOnWayProcess($shipped_initial_data, $initial_price, $sitting_price, $cancel_price, $option_price);

        $client = $this->clientService->getOrderedClientData($this->data['order_id'], ['client_id', 'phone']);
        $driver = $this->orderContract->getOrderedDriverData($this->data['order_id'], ['driver_id', 'car_id', 'current_franchise_id', 'phone']);

        $driver ? PassLivePrice::broadcast($driver, $price, $distance, 0, $duration) : null;
        $client ? ClientPassOrderPrice::broadcast($client, $price, $shipped_initial_data->sitting_price) : null;
    }

    /**
     * @param  object  $shipped_initial_data
     * @return float|string
     */
    protected function calcSitPrice(object $shipped_initial_data): float|string
    {
        $tariff = $shipped_initial_data->initial_order_tariff->load('current_tariff');

        if (!$tariff->current_tariff->cancel_fee) {
            return 0.0;
        }

        return $tariff->current_tariff->cancel_fee ?? '0.0';
    }

    /**
     * @param $shipped
     * @param  float|int|string  $initial_price
     * @param  float|int|string|null  $sitting
     * @param  float|int|string|null  $cancel
     * @param  float|int|string|null  $options
     * @return float|null
     */
    protected function createOnWayProcess(
        $shipped,
        float|int|string $initial_price,
        float|int|string $sitting = null,
        float|int|string $cancel = null,
        float|int|string $options = null
    ): ?float {
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

        $this->processContract->updateOrCreate(
            ['order_shipped_id', '=', $shipped->order_shipped_driver_id],
            [
                'order_shipped_id' => $shipped->order_shipped_driver_id,
                'cord_updated' => $this->data['now'],

                'options_price' => $options,
                'sitting_price' => $sitting,
                'cancel_price' => $cancel,

                'price' => $initial_price,
                'total_price' => $initial_price + $options + $sitting,
                'calculate_price' => $shipped['initial_order_tariff']['minimal_price'] + $options + $sitting,
            ]
        );

        return $initial_price + $options + $sitting;
    }
}
