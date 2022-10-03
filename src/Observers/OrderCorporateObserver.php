<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\Order\OrderUpdateEvent;
use Src\Repositories\Order\OrderContract;

/**
 *
 */
class OrderCorporateObserver extends BaseObserver
{
    public function __construct(protected OrderContract $orderContract)
    {
    }

    /**
     * @param  Model  $model
     */
    public function creating(Model $model): void
    {
        // TODO: Implement creating() method.
    }

    /**
     * @param  Model  $model
     */
    public function created(Model $model): void
    {
        // TODO: Implement created() method.
    }

    /**
     * @param  Model  $model
     */
    public function updating(Model $model): void
    {
        // TODO: Implement updating() method.
    }

    /**
     * @param  Model  $model
     */
    public function updated(Model $model): void
    {
        $order = $this->orderContract
            ->where('order_id', '=', $model->order_id)
            ->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->whereHas('completed')
            ->whereHas('corporate')
            ->with(['corporate', 'completed'])
            ->findFirst();

        event(new OrderUpdateEvent($order));
    }

    /**
     * @param  Model  $model
     */
    public function saving(Model $model): void
    {
        // TODO: Implement saving() method.
    }

    /**
     * @param  Model  $model
     */
    public function saved(Model $model): void
    {
        // TODO: Implement saved() method.
    }

    /**
     * @param  Model  $model
     */
    public function deleting(Model $model): void
    {
        // TODO: Implement deleting() method.
    }

    /**
     * @param  Model  $model
     */
    public function deleted(Model $model): void
    {
        // TODO: Implement deleted() method.
    }
}
