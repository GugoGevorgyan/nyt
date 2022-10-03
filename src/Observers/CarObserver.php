<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Models\Car\Car;

/**
 * Class CarObserver
 * @package Src\Observers
 */
class CarObserver extends BaseObserver
{
    /**
     * @param  Car  $car
     */
    public function created(Model $car): void
    {
    }

    /**
     * @param  Model  $car
     */
    public function updated(Model $car): void
    {
    }

    /**
     * @param  Car  $car
     */
    public function deleted(Model $car): void
    {
        //
    }

    /**
     * @param  Car  $car
     */
    public function restored(Model $car): void
    {
    }

    /**
     * @param  Car  $car
     */
    public function forceDeleted(Model $car): void
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
