<?php

declare(strict_types=1);

namespace App\Kernels;

use App\Commands\StorageLinkCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as BaseConsoleKernel;
use Src\Console\Commands\AddressMonitorWriter;
use Src\Console\Commands\CheckDriversPenalties;
use Src\Console\Commands\CheckPreOrderTimeOut;
use Src\Console\Commands\DriverLockedCommand;
use Src\Console\Commands\DriverWaybillCalculate;
use Src\Console\Commands\TrafficSafetyCheck;
use Src\Models\Task;

/**
 * Class Kernel
 * @package Src\Console
 */
class Console extends BaseConsoleKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
//    protected $bootstrappers = [
//        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
//        \App\Configures\Config\LoadConfigurator::class,
//        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
//        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
//        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
//        \Illuminate\Foundation\Bootstrap\BootProviders::class,
//    ];

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AddressMonitorWriter::class,
        DriverLockedCommand::class,
        DriverWaybillCalculate::class,
        TrafficSafetyCheck::class,
        StorageLinkCommand::class,
        CheckPreOrderTimeOut::class,
        CheckDriversPenalties::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $tasks = Task::all();

        foreach ($tasks as $task) {
            if ($task->status) {
                $every = $task->every;
                $schedule->command($task->command)->$every()->withoutOverlapping();
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console/console.php');
    }
}
