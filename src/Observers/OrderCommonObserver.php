<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\Order\OrderCommonCreateEvent;
use Src\Events\Order\OrderCommonUpdateEvent;
use Src\Models\Order\OrderCommon;

/**
 * Class OrderCommonObserver
 * @package Src\Observers
 */
class OrderCommonObserver extends BaseObserver
{
    /**
     * @param OrderCommon $common
     */
    public function created(Model $common): void
    {
        event(new OrderCommonCreateEvent($common));
    }

    /**
     * @param OrderCommon $common
     */
    public function updated(Model $common): void
    {
        event(new OrderCommonUpdateEvent($common));
    }

    /**
     * @param OrderCommon $common
     */
    public function deleted(Model $common): void
    {
        //
    }

    /**
     * @param OrderCommon $common
     */
    public function restored(Model $common): void
    {
        event(new OrderCommonUpdateEvent($common));
    }

    /**
     * @param OrderCommon $common
     */
    public function forceDeleted(Model $common): void
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
