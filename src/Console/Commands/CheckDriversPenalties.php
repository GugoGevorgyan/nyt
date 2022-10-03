<?php

declare(strict_types=1);

namespace Src\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverDebt\DebtContract;
use Src\Repositories\DriverWallet\DriverWalletContract;
use Src\Repositories\Penalty\PenaltyContract;
use Src\Services\Driver\DriverService;

class CheckDriversPenalties extends Command
{
    private const PENALTY_TYPE_ID = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:drivers_penalties';
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
        protected DriverContract $driverContract,
        protected DriverService $driverService,
        protected DriverWalletContract $driverWalletContract,
        protected DebtContract $debtContract,
        protected PenaltyContract $penaltyContract
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
        $penalties = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('GIBDD_API_KEY')
        ])->get('https://api.onlinegibdd.ru/v3/partner_fines');

        $this->driverHandling((array)$penalties['data']['auto_list']);
    }

    /**
     * @param $penalties
     */
    protected function driverHandling($penalties): void
    {
        foreach ($penalties as $key) {
            $driver = $this->driverContract->whereHas('car', fn($q) => [
                $q->where('sts_number', '=', $key['auto_cdi'])
            ])->findFirst(['driver_id']);

            if ($driver) {
                foreach ($key['offense_list'] as $k) {
                    if ($k['offense_date'] = (Carbon::today()->toDateString() && Carbon::parse($k['offense_time'])->greaterThan(Carbon::create('10:00')))) {
                        $dataDebt = [
                            'debtor_id' => $driver['driver_id'],
                            'debtor_type' => $driver->getMap(),
                            'type' => self::PENALTY_TYPE_ID,
                            'cost' => $k['pay_bill_amount'],
                            'cost_paid' => 'nopayed' === $k['gis_status'] ? 0 : $k['pay_bill_amount'],
                        ];

                        $debt = $this->debtContract->create($dataDebt);

                        $dataPenalty = [
                            'debt_id' => $debt['debt_id'],
                            'offense_id' => $k['gis_id'],
                            'offense_date' => $k['offense_date'],
                            'offense_time' => $k['offense_time'],
                            'offense_location' => $k['offense_location'],
                            'pay_bill_date' => $k['pay_bill_date'],
                            'last_bill_date' => $k['last_bill_date'],
                            'lat' => $k['offense_latitude'],
                            'lut' => $k['offense_longitude'],
                            'status' => 'nopayed' === $k['gis_status'] ? 0 : 1,
                        ];

                        $this->penaltyContract->updateOrCreate(['offense_id', '=', $k['gis_id']], $dataPenalty);

                        $current_debt = $this->driverService->getDebt($driver['driver_id'])['debt'];

                        if ('nopayed' === $k['gis_status']) {
                            $this->driverWalletContract
                                ->where('driver_id', '=', $driver['driver_id'])
                                ->updateSet(['debt' => $current_debt + $k['pay_bill_amount']]);
                        }
                    }
                }
            }
        }
    }
}
