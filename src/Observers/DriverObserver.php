<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\Driver\DriverUpdateEvent;
use Src\Models\Driver\Driver;

/**
 * Class DriverObserver
 * @package Src\Observers
 */
class DriverObserver extends BaseObserver
{
    /**
     * @param  Driver  $driver
     */
    public function created(Model $driver): void
    {
    }


    /**
     * @param  Driver  $driver
     */
    public function updated(Model $driver): void
    {
        event(new DriverUpdateEvent($driver));
    }

    /**
     * @param  Driver  $driver
     */
    public function deleted(Model $driver): void
    {
        //
    }

    /**
     * @param  Driver  $driver
     */
    public function restored(Model $driver): void
    {
//        event(new DriverUpdateEvent($driver));
    }

    /**
     * @param  Driver  $driver
     */
    public function forceDeleted(Model $driver): void
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
