<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Events\SystemWorker\FranchiseTransactionCrud;
use Src\Models\Franchise\FranchiseTransaction;

/**
 * Class FranchiseTransactionObserver
 * @package Src\Observers
 */
class FranchiseTransactionObserver extends BaseObserver
{
    /**
     * @param  FranchiseTransaction  $transaction
     */
    public function created(Model $transaction): void
    {
        FranchiseTransactionCrud::dispatch($transaction, 'created');
    }

    /**
     * @param  FranchiseTransaction  $transaction
     */
    public function updated(Model $transaction): void
    {
        FranchiseTransactionCrud::dispatch($transaction);
    }

    /**
     * @param  FranchiseTransaction  $transaction
     */
    public function deleted(Model $transaction): void
    {
        FranchiseTransactionCrud::dispatch($transaction);
    }

    /**
     * @param  FranchiseTransaction  $transaction
     */
    public function restored(Model $transaction): void
    {
        FranchiseTransactionCrud::dispatch($transaction);
    }

    /**
     * @param  FranchiseTransaction  $transaction
     */
    public function forceDeleted(Model $transaction): void
    {
        FranchiseTransactionCrud::dispatch($transaction);
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
