<?php

declare(strict_types=1);

namespace Src\Console\Commands;

use Illuminate\Console\Command;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverWallet\DriverWalletContract;
use Src\Services\Terminal\TerminalServiceContract;

/**
 * Class DriverWaybillCalculate
 * @package Src\Console\Commands
 */
class DriverWaybillCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:waybill_calculate';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected TerminalServiceContract $terminalService,
        protected DriverContract $driverContract,
        protected DriverWalletContract $driverWalletContract
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->driverContract
            ->has('active_contract')
            ->with([
                'active_contract' => fn($query) => $query->select(['driver_contract_id', 'driver_id', 'busy_days_price', 'free_days_price']),
                'last_active_waybill' => fn($query) => $query->select(['waybill_id', 'terminal_id', 'car_id', 'driver_id', 'start_time', 'end_time']),
            ])
            ->findAll(['driver_id'])
            ->chunk(50)
            ->map(fn($driver) => $this->driverHandling($driver));

        $this->info('Checked Waybill days payd');
    }

    /**
     * @param $drivers
     */
    protected function driverHandling($drivers): void
    {
        foreach ($drivers as $driver) {
            if ($driver->last_active_waybill) {
                $todayUnpaid = $this->terminalService->getScheduleUnpaidOfToday($driver, $driver->last_active_waybill->end_time);

                if (now() > $todayUnpaid[0]['date_time']) {
                    $driverUnpaidDay_debt = $todayUnpaid[0]['working']
                        ? $driver->active_contract->busy_days_price
                        : $driver->active_contract->free_days_price;
                    $debt = $this->driverWalletContract->where('driver_id', '=', $driver->driver_id)->findFirst(['driver_id', 'debt']);
                    $all_debt = $debt ? $debt['debt'] : 0 + $driverUnpaidDay_debt;
                    $this->driverWalletContract->where('driver_id', '=', $driver->driver_id)->updateSet(['debt' => $all_debt]);
                    $this->driverContract->where('driver_id', '=', $driver->driver_id)->updateSet(['is_ready' => 0]);
                }
            }
        }
    }
}
