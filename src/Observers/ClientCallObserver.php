<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\ClientCall\ClientCallCreateEvent;
use Src\Events\ClientCall\ClientCallUpdateEvent;
use Src\Models\Client\ClientCall;

/**
 * Class ClientCallObserver
 * @package Src\Observers
 */
class ClientCallObserver extends BaseObserver
{
    /**
     * @param  ClientCall  $clientCall
     */
    public function created(Model $clientCall): void
    {
        event(new ClientCallCreateEvent($clientCall));
    }

    /**
     * @param  ClientCall  $clientCall
     */
    public function updated(Model $clientCall): void
    {
        event(new ClientCallUpdateEvent($clientCall));
    }

    /**
     * @param  ClientCall  $clientCall
     */
    public function deleted(Model $clientCall): void
    {
        //
    }

    /**
     * @param  ClientCall  $clientCall
     */
    public function restored(Model $clientCall): void
    {
        event(new ClientCallUpdateEvent($clientCall));
    }

    /**
     * @param  ClientCall  $clientCall
     */
    public function forceDeleted(Model $clientCall): void
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
