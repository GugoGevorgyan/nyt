<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\Order\OrderAttachEvent;
use Src\Events\Order\OrderCreateEvent;
use Src\Events\Order\OrderStatusUpdate;
use Src\Events\Order\OrderUpdateEvent;
use Src\Models\Order\Order;

/**
 * Class OrderObserver
 * @package Src\Observers
 */
class OrderObserver extends BaseObserver
{
    /**
     * @param  Order  $order
     */
    public function created(Model $order): void
    {
        event(new OrderCreateEvent($order));
    }

    /**
     * @param  Order  $order
     */
    public function updated(Model $order): void
    {
        event(new OrderUpdateEvent($order));

        if ($order->isDirty('operator_id')) {
            event(new OrderAttachEvent($order));
        }

        if ($order->wasChanged('status_id')) {
            event(new OrderStatusUpdate($order));
        }
    }

    /**
     * @param  Order  $order
     */
    public function deleted(Model $order): void
    {
        //
    }

    /**
     * @param  Order  $order
     */
    public function restored(Model $order): void
    {
        event(new OrderUpdateEvent($order));
    }

    /**
     * @param  Order  $order
     */
    public function forceDeleted(Model $order): void
    {
        //
    }

    public function creating(Model $model): void
    {
        // TODO: Implement creating() method.
    }

    public function updating(Model $model): void
    {
        // TODO: Implement updating() method.
    }

    public function saving(Model $model): void
    {
        // TODO: Implement saving() method.
    }

    public function saved(Model $model): void
    {
        // TODO: Implement saved() method.
    }

    public function deleting(Model $model): void
    {
        // TODO: Implement deleting() method.
    }
}
