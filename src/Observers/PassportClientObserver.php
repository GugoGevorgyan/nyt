<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Client;
use Src\Core\Contracts\BaseObserver;

/**
 * Class PassportClientObserver
 * @package Src\Observers
 */
class PassportClientObserver extends BaseObserver
{
    /**
     * @param  Client  $client
     */
    public function created(Model $client): void
    {
//        $client->update(['device' => \Agent::device()]);
    }

    /**
     * @param  Client  $client
     */
    public function updated(Model $client): void
    {
//        $client->update(['device' => \Agent::device()]);
    }

    /**
     * @param  Client  $client
     */
    public function deleted(Model $client): void
    {
        //
    }

    /**
     * @param  Client  $client
     */
    public function restored(Model $client): void
    {
        //
    }

    /**
     * @param  Client  $client
     */
    public function forceDeleted(Model $client): void
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
