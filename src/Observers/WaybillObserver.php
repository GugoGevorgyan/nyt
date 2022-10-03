<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Client;
use Src\Core\Contracts\BaseObserver;
use Src\Events\Waybill\WaybillCreate;
use Src\Models\Order\Order;
use Src\Models\Terminal\Waybill;

/**
 * Class WaybillObserver
 * @package Src\Observers
 */
class WaybillObserver extends BaseObserver
{
    /**
     * @param  Waybill  $waybill
     */
    public function created(Model $waybill): void
    {
        event(new WaybillCreate($waybill));
    }

    /**
     * @param  Waybill  $client
     */
    public function updated(Model $client): void
    {
    }

    /**
     * @param  Waybill  $client
     */
    public function deleted(Model $client): void
    {
    }

    /**
     * @param  Waybill  $client
     */
    public function restored(Model $client): void
    {
    }

    /**
     * @param  Waybill  $client
     */
    public function forceDeleted(Model $client): void
    {
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
