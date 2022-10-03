<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Src\Broadcasting\Broadcast\Worker\BookkeepingCreateTransaction;
use Src\Core\Enums\ConstQueue;
use Src\Events\SystemWorker\FranchiseTransactionCrud;
use Src\Http\Resources\Worker\Bookkeeping\BookkeepingData;
use Src\Models\Role\Role;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * Class FranchiseTransactionCrudListen
 * @package Src\Listeners\Worker
 */
class FranchiseTransactionCrudListen
{
    /**
     * @var FranchiseTransactionCrud
     */
    protected FranchiseTransactionCrud $event;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(protected SystemWorkerContract $workerContract)
    {
    }

    /**
     * Handle the event.
     *
     * @param  FranchiseTransactionCrud  $event
     * @return void
     */
    public function handle(FranchiseTransactionCrud $event): void
    {
        $this->event = $event;

        $this->workersDistributor();
    }

    protected function workersDistributor(): void
    {
        $this->workerContract
            ->where('is_admin', '=', true)
            ->orWhereHas('roles', fn($query) => $query->where('name', '=', Role::ACCOUNTANT_WEB)->orWhere('name', '=', Role::ACCOUNTANT_API))
            ->where('franchise_id', '=', $this->event->franchiseTransaction->franchise_id)
            ->findAll(['system_worker_id', 'franchise_id'])
            ->map(fn($item) => BookkeepingCreateTransaction::broadcast($item, new BookkeepingData($this->event->franchiseTransaction)));
    }

    /**
     * @return string
     */
    public function viaQueue(): string
    {
        return ConstQueue::OBSERVER()->getValue();
    }
}
