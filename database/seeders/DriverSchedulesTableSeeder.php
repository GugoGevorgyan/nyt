<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Driver\DriverContract;
use Src\Models\Driver\DriverSchedule;

/**
 * Class DriverSchedulesTableSeeder
 */
class DriverSchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws JsonException
     */
    public function run()
    {
        $contracts = DriverContract::get();

        foreach ($contracts as $iValue) {
            $contract = $iValue;

            $contract->load('graphic');
            $date = $contract->signing_day;
            $week_day = 0;
            $week = decode($contract->graphic->week);

            while (strtotime($date) <= strtotime($contract->expiration_day)) {
                $week_day = $week_day === count($week['values']) ? 0 : $week_day;
                $working = false;

                if (strtotime($date) >= strtotime($contract->work_start_day)) {
                    $working = $week['values'][$week_day];
                    $week_day++;
                }

                $schedule_data = [
                    'driver_id' => $contract->driver_id,
                    'driver_contract_id' => $contract->driver_contract_id,
                    'working' => $working,
                    'date' => $date,
                    'day' => date('j', strtotime($date)),
                    'month' => date('n', strtotime($date)),
                    'year' => date('Y', strtotime($date)),
                ];

                DriverSchedule::create($schedule_data);
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            }
        }
    }
}
