<?php

declare(strict_types=1);

namespace Src\Console\Commands;

use Illuminate\Console\Command;
use Src\Events\TrafficSafetyCheckEvent;
use Src\Repositories\Car\CarContract;

/**
 * Class TrafficSafetyCheck
 * @package Src\Console\Commands
 */
class TrafficSafetyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trafficSafety:check';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check cars insurance and inspection expiration';

    /**
     * Create a new console command instance.
     *
     * @param  CarContract  $carContract
     */
    public function __construct(protected CarContract $carContract)
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
        $expired_inspection_cars = $this->carContract
            ->where('inspection_expiration_date', '=', date('Y-m-d', strtotime('-1 day')))
            ->with([
                'drivers',
                'park' => fn($q) => $q->with('parkManager')
            ])
            ->findAll();

        $this->info("Expired inspection: {$expired_inspection_cars->count()} car(s)");

        $expired_inspection_cars->map(fn($car) => $this->carContract->update($car->car_id, ['status' => 'EMERGENCY']));

        $leftDayInspection = $this->carContract
            ->where('inspection_expiration_date', '=', date('Y-m-d', strtotime("+1 day")))
            ->with([
                'drivers' => fn($q) => $q->select(['*']),
                'park' => fn($q) => $q->select(['*'])->with('parkManager')
            ])
            ->findAll();

        $this->info("Left 1 day inspection: {$leftDayInspection->count()} car(s)");

        $leftWeekInspection = $this->carContract
            ->where('insurance_expiration_date', '=', date('Y-m-d', strtotime("+7 day")))
            ->with([
                'drivers',
                'park' => fn($q) => $q->with('parkManager')
            ])
            ->findAll();

        $this->info("Left 7 day inspection: {$leftWeekInspection->count()} car(s)");

        $expiredInsurance = $this->carContract
            ->where('insurance_expiration_date', '=', date('Y-m-d', strtotime("-1 day")))
            ->with([
                'drivers',
                'park' => fn($q) => $q->with('parkManager')
            ])
            ->findAll();

        $this->info("Expired insurance: {$expiredInsurance->count()} car(s)");

        $expiredInsurance->map(fn($car) => $car->update(['status' => 'EMERGENCY']));

        $leftDayInsurance = $this->carContract
            ->where('insurance_expiration_date', '=', date('Y-m-d', strtotime("+1 day")))
            ->with([
                'drivers',
                'park' => fn($q) => $q->with('parkManager')
            ])
            ->findAll();

        $this->info("Left 1 day insurance: {$leftDayInsurance->count()} car(s)");

        $leftWeekInsurance = $this->carContract
            ->where('insurance_expiration_date', '=', date('Y-m-d', strtotime("+7 day")))
            ->with([
                'drivers',
                'park' => fn($q) => $q->with('parkManager')
            ])
            ->findAll();

        $this->info("Left 7 day insurance: {$leftWeekInsurance->count()} car(s)");

        $cars = compact('expired_inspection_cars', 'leftDayInspection', 'leftWeekInspection', 'expiredInsurance', 'leftDayInsurance', 'leftWeekInsurance');

        TrafficSafetyCheckEvent::dispatch($cars);
    }
}
