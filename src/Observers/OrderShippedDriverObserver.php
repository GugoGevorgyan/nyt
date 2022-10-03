<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\Order\OrderAcceptEvent;
use Src\Events\Order\OrderShippedDriverEvent;
use Src\Models\Order\OrderShippedDriver;
use Src\Models\Order\OrderShippedStatus;

/**
 * Class OrderShippedDriverObserver
 * @package Src\Observers
 */
class OrderShippedDriverObserver extends BaseObserver
{
    /**
     * @param  OrderShippedDriver  $shipped
     */
    public function created(Model $shipped): void
    {
        if ($shipped->current) {
            event(new OrderShippedDriverEvent(['shipped_id' => (int)$shipped->order_shipped_driver_id]));
        }

        if ($shipped->current && $shipped->status_id === OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_ACCEPTED)){
            event(new OrderAcceptEvent($shipped->order));
        }
    }

    /**
     * @param  OrderShippedDriver  $shipped
     */
    public function updated(Model $shipped): void
    {
        event(new OrderShippedDriverEvent(['shipped_id' => (int)$shipped->order_shipped_driver_id]));
    }

    /**
     * @param  OrderShippedDriver  $shipped
     */
    public function deleted(Model $shipped): void
    {
        //
    }

    /**
     * @param  OrderShippedDriver  $shipped
     */
    public function restored(Model $shipped): void
    {
        //
    }

    /**
     * @param  OrderShippedDriver  $shipped
     */
    public function forceDeleted(Model $shipped): void
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
