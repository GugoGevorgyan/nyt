<?php

declare(strict_types=1);

namespace Src\Console\Commands;

use Illuminate\Console\Command;
use ServiceEntity\BaseService;
use Src\Repositories\Preorder\PreorderContract;

/**
 *
 */
class CheckPreOrderTimeOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorder:timeout';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete old preorders where time outed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected PreorderContract $preorderContract, protected BaseService $baseService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->preorderContract
            ->where('active', '=', true)
            ->where('time', '<', now())
            ->findAll(['active', 'time', 'order_id'])
            ->map(fn($item) => $this->baseService->removeRedisKeys($item['order_id']));

        $this->preorderContract
            ->where('active', '=', true)
            ->where('time', '<', now())
            ->updateSet(['active' => false]);

        $this->info('Old preorders deletes');
    }
}
