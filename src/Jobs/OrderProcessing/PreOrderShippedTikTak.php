<?php

declare(strict_types=1);

namespace Src\Jobs\OrderProcessing;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Core\Traits\Complex;

/**
 *
 */
class PreOrderShippedTikTak implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;
    use Complex;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected object $driver, public array $payload, protected int $tik_time = 30)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    }
}
