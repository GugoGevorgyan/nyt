<?php

declare(strict_types=1);

namespace Src\Console\Commands;

use Illuminate\Console\Command;
use Src\Broadcasting\Broadcast\Driver\LockedInfo;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverLock\DriverLockContract;

/**
 * Class DriverLockedCommand
 * @package Src\Console\Commands
 */
class DriverLockedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers_locked:check';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and toggle driver locked';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected DriverLockContract $lockContract, protected DriverContract $driverContract)
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
        $lockeds = $this->lockContract
            ->where('locked', '=', true)
            ->where('end', '<=', f_now())
            ->findAll();

        if (!$lockeds->count()) {
            $this->info('Checked');
            return;
        }

        foreach ($lockeds as $lock) {
            $this->lockContract->update($lock->driver_lock_id, ['locked' => false]);
            $driver = $this->driverContract->find((int)$lock->driver_id, ['driver_id', 'car_id', 'phone', 'current_franchise_id']);
            LockedInfo::broadcast($driver, ['message' => trans('messages.driver_locked_dawn'), 'locked' => false, 'time' => 0]);
        }

        $this->info('Checked');
    }
}
