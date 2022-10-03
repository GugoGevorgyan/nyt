<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\SystemWorker\WorkerOperatorUpdateEvent;
use Src\Models\SystemUsers\WorkerOperator;

/**
 * Class WorkerOperatorObserver
 * @package Src\Observers
 */
class WorkerOperatorObserver extends BaseObserver
{
    /**
     * @param  WorkerOperator  $workerOperator
     */
    public function created(Model $workerOperator): void
    {
    }


    /**
     * @param  WorkerOperator  $workerOperator
     */
    public function updated(Model $workerOperator): void
    {
        event(new WorkerOperatorUpdateEvent($workerOperator));
    }

    /**
     * @param  WorkerOperator  $workerOperator
     */
    public function deleted(Model $workerOperator): void
    {
        //
    }

    /**
     * @param  WorkerOperator  $workerOperator
     */
    public function restored(Model $workerOperator): void
    {
        event(new WorkerOperatorUpdateEvent($workerOperator));
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
