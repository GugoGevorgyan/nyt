<?php

declare(strict_types=1);

namespace Src\Jobs\OrderProcessing;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Broadcasting\Broadcast\Client\SearchDriverTimeoutEvent;
use Src\Core\Additional\Devicer;
use Src\Core\Enums\ConstOrderDistType;
use Src\Core\Enums\ConstQueueUuid;
use Src\Core\Traits\Complex;
use Src\Events\Order\DistributeOrder;
use Src\Models\Order\Order;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Services\Client\ClientServiceContract;

/**
 * Class OrderDistributor
 * @package Src\Jobs
 * @property  ClientServiceContract $clientService
 * @property  OrderCommonContract $orderCommonContract
 * @property  TariffContract $tariffContract
 * @property  OrderContract $orderContract
 */
class OrderDistributor implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Complex;

    /**
     * @var int
     */
    public int $timeout = 600;
    /**
     * @var int
     */
    public int $tries = 1;

    /**
     * Create a new event instance.
     *
     * @param  Order  $order
     * @param  array  $orderData
     * @param  float  $price
     */
    public function __construct(protected Order $order, protected array $orderData, protected float $price = 0.0)
    {
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        set_queue_id(ConstQueueUuid::order_distribute()->getValue().":{$this->order->order_id}", $this->job->getJobId());

        $this->inject(ClientServiceContract::class, OrderCommonContract::class, TariffContract::class, OrderContract::class);

        $timer = now();

        if (!$this->order) {
            return;
        }

        if ($this->detectCoinLimit() && $this->detectCoinLimit() <= $this->price) {
            $this->orderCommonContract->updateOrCreate(['order_id', '=', $this->order->order_id], ['emergency' => true, 'order_id' => $this->order->order_id]);
            return;
        }

        DistributeOrder::dispatch($this->order, $this->orderData, $timer);

        $this->timeEnd();

        rem_queue_id(ConstQueueUuid::order_distribute()->getValue().":{$this->order->order_id}");
    }

    /**
     * @return null|string
     */
    protected function detectCoinLimit(): ?string
    {
        return $this->tariffContract
                ->whereHas('initial', fn(Builder $query) => $query->where('order_id', '=', $this->order->order_id))
                ->findFirst(['tariff_id', 'limit_manually_cost'])
                ->limit_manually_cost ?? '0';
    }

    /**
     *
     */
    protected function timeEnd(): void
    {
        $this->orderContract->update($this->order->order_id, ['dist_type' => ConstOrderDistType::manual()->getValue()]);
    }

    /**
     * @param  Exception  $exception
     */
    public function failed(Exception $exception): void
    {
        $client = $this->clientService->getOrderedClientData($this->order->order_id);

        if ($client) {
            SearchDriverTimeoutEvent::broadcast($client->client_id, $client->phone, $this->order->order_id, (new Devicer())->isMobile());
        }
    }
}
