<?php

declare(strict_types=1);

namespace Src\Events\SystemWorker;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\FranchiseTransactionCrudListen;
use Src\Models\Franchise\FranchiseTransaction;

/**
 * Class FranchiseTransactionCrudListen
 * @package Src\Events\SystemWorker
 * @link FranchiseTransactionCrudListen
 */
class FranchiseTransactionCrud
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public FranchiseTransaction $franchiseTransaction, public $observer_type)
    {
    }
}
