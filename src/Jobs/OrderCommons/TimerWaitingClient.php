<?php

declare(strict_types=1);

namespace Src\Jobs\OrderCommons;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Broadcasting\Broadcast\Client\TimeFreeWaitEnd;
use Src\Models\Driver\DriverStatus;
use Src\Repositories\Driver\DriverContract;

/**
 * Class TimerWaitingClient
 * @package Src\Jobs
 */
class TimerWaitingClient implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $client
     * @param $driver
     * @param $paidMinute
     */
    public function __construct(protected $client, protected $driver, protected $paidMinute)
    {
    }

    /**
     * Execute the job.
     *
     * @param  DriverContract  $driverContract
     * @return void
     */
    public function handle(DriverContract $driverContract): void
    {
        $status = $driverContract
            ->where($driverContract->getKeyName(), $this->driver['driver_id'])
            ->where('current_status_id', '=', DriverStatus::DRIVER_IN_PLACE)
            ->exists();

        if ($status) {
            TimeFreeWaitEnd::broadcast($this->client, $this->driver,
                trans('messages.client_free_wait', ['price' => $this->paidMinute, 'currency' => session('app_system.currency')]));
        }
    }
}
