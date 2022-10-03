<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\SystemWorker\WorkerOperatorCreateEvent;
use Src\Models\Role\Role;
use Src\Models\SystemUsers\SystemWorker;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Repositories\WorkerOperator\WorkerOperatorContract;

/**
 * Class SystemWorkerObserver
 * @package Src\Observers
 */
class SystemWorkerObserver extends BaseObserver
{
    public function __construct(protected SystemWorkerContract $workerContract, protected WorkerOperatorContract $operatorContract)
    {
    }

    /**
     * @param  SystemWorker  $systemWorker
     */
    public function created(Model $systemWorker): void
    {
        $this->operatorEvent($systemWorker);
    }

    /**
     * @param $systemWorker
     */
    protected function operatorEvent($systemWorker): void
    {
        $has_operator = $this->workerContract
            ->whereHas('roles', fn(Builder $query) => $query->whereIn('name', [Role::OPERATOR_API, Role::OPERATOR_WEB]))
            ->find($systemWorker->{$systemWorker->getKeyName()}, [$systemWorker->getKeyName()]);

        $operator = $this->operatorContract->where('system_worker_id', '=', $systemWorker->{$systemWorker->getKeyName()})->findFirst();

        if ($has_operator && $operator) {
            event(new WorkerOperatorCreateEvent($operator));
        }
    }

    /**
     * @param  SystemWorker  $systemWorker
     */
    public function updated(Model $systemWorker): void
    {
        $this->operatorEvent($systemWorker);
    }

    /**
     * @param  SystemWorker  $systemWorker
     */
    public function deleted(Model $systemWorker): void
    {
        //
    }

    /**
     * @param  SystemWorker  $systemWorker
     */
    public function restored(Model $systemWorker): void
    {
    }

    /**
     * @param  SystemWorker  $systemWorker
     */
    public function forceDeleted(Model $systemWorker): void
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
