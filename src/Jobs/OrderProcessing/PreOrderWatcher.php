<?php

declare(strict_types=1);

namespace Src\Jobs\OrderProcessing;

use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstOrderDistType;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstRedis;
use Src\Core\Enums\ConstTariffLocality;
use Src\Exceptions\Order\OrderCanceledInSearchDriverException;
use Src\Listeners\Order\Distributor\Traits\OrderDistDrivers;
use Src\Listeners\Order\Distributor\Traits\OrderDistFilters;
use Src\Models\Order\OrderShippedStatus;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Order\OrderServiceContract;

/**
 * @method static dispatch(int $order_id, float|int $delay_start, ?int $nearObject);
 */
class PreOrderWatcher implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;
    use OrderDistDrivers;
    use OrderDistFilters;

    /**
     * @var Carbon|null
     */
    protected ?Carbon $justNow = null;

    /**
     * @var OrderContract
     */
    protected OrderContract $orderContract;
    /**
     * @var PreorderContract
     */
    protected PreorderContract $preorderContract;
    /**
     * @var OrderShippedDriverContract
     */
    protected OrderShippedDriverContract $shippedContract;
    /**
     * @var OrderServiceContract
     */
    protected OrderServiceContract $orderService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected int $orderId, protected float|int $delay_start, protected ?int $nearObject = null)
    {
        $this->justNow = now();
    }

    /**
     * Execute the job.
     *
     * @param  OrderContract  $orderContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  PreorderContract  $preorderContract
     * @param  OrderServiceContract  $orderService
     * @return void
     * @throws OrderCanceledInSearchDriverException
     */
    public function handle(
        OrderContract $orderContract,
        OrderShippedDriverContract $shippedContract,
        PreorderContract $preorderContract,
        OrderServiceContract $orderService
    ): void {
        $this->orderContract = $orderContract;
        $this->orderService = $orderService;
        $this->shippedContract = $shippedContract;
        $this->preorderContract = $preorderContract;

        if ($this->orderService->orderHasCanceled($this->orderId, true) || $this->checkWhenMeek() || $this->checkWhenManual()) {
            return;
        }

        if (!$this->checkExistsPreDriver()) {
            $this->preorderContract->where('order_id', '=', $this->orderId)->updateSet(['distribution_start' => $this->justNow]);
            $this->orderService->autoDispatch($this->orderId);
        }

        $preorder = $this->preorderContract->where('order_id', '=', $this->orderId)->findFirst(['order_id', 'time']);

        if ($preorder && Carbon::parse($preorder->time)->diffInMinutes($this->justNow) > 120) {
            if ($this->nearObject === ConstTariffLocality::from_object()->getValue()) {
                $this->rec(50);
            }

            if ($this->nearObject === ConstTariffLocality::from_airport()->getValue() || $this->nearObject === ConstTariffLocality::region()->getValue()) {
                $this->rec(30);
            }

            $this->rec(60);
        }

        $this->preorderContract->where('order_id', '=', $this->orderId)->updateSet(['distribution_start' => $this->justNow]);
        $this->orderService->autoDispatch($this->orderId);
    }

    /**
     * @return bool
     */
    protected function checkWhenManual(): bool
    {
        $order = $this->orderContract->find($this->orderId, ['order_id', 'dist_type']);

        return $order && $order->dist_type !== ConstOrderDistType::automatic()->getValue();
    }

    /**
     * @return bool
     */
    protected function checkWhenMeek(): bool
    {
        return $this->shippedContract
            ->where('order_id', '=', $this->orderId)
            ->where(fn($query) => $query
                ->where('status_id', '=', OrderShippedStatus::PRE_PENDING)
                ->orWhere('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
            )
            ->exists();
    }

    /**
     * @return bool
     */
    protected function checkExistsPreDriver(): bool
    {
        $preorder = $this->preorderContract->where('order_id', '=', $this->orderId)->findFirst(['order_id', 'driver']);

        return (bool)($preorder->driver['ids'] ?? false);
    }

    /**
     * Dispatch this class nan minute
     */
    protected function rec($minute): void
    {
        self::class::dispatch($this->orderId, $this->nearObject)
            ->delay($this->justNow->addMinutes($minute))
            ->onQueue(ConstQueue::LONG()->getValue());
    }

    /**
     * @return array|null
     */
    protected function twiceData(): ?array
    {
        $_order = redis()->hMGet(ConstRedis::order_create_data($this->orderId), ['price_data', 'order_data']);

        if (!$_order[0] || !$_order[1]) {
            return [[], []];
        }

        $_price_data = igus($_order[0]);
        $_order_data = igus($_order[1]);

        return [$_price_data, $_order_data];
    }
}
