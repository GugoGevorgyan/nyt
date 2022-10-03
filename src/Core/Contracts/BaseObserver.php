<?php

declare(strict_types=1);

namespace Src\Core\Contracts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Src\Core\Enums\ConstQueue;

/**
 *
 */
abstract class BaseObserver implements ShouldQueue
{
    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function creating(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function created(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function updating(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function updated(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function saving(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function saved(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function deleting(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    abstract public function deleted(Model $model): void;

    /**
     * @param  Model  $model
     * @return void
     */
    public function restoring(Model $model): void
    {
        // @todo restoring body
    }

    /**
     * @param  Model  $model
     * @return void
     */
    public function restored(Model $model): void
    {
        // @todo restored body
    }

    /**
     * @param  Model  $model
     * @return void
     */
    public function retrieved(Model $model): void
    {
        // @todo retrieved body
    }

    /**
     * @param  Model  $model
     * @return void
     */
    public function forceDeleted(Model $model): void
    {
        // @todo restored body
    }

    /**
     * @param  Model  $model
     * @return void
     */
    public function replicating(Model $model): void
    {
        // @todo replicating body
    }

    /**
     * @return string
     */
    final public function viaQueue(): string
    {
        return ConstQueue::OBSERVER()->getValue();
    }
}
