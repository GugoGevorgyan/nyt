<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Src\Models\Driver\DriverStatus;

/**
 * Class DriversCurrentStatusSeeder
 */
class DriversCurrentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('driver_statuses')->delete();

        DB::table('driver_statuses')->insert([

            0 => [
                'driver_status_id' => 1,
                'name' => 'IS_FREE',
                'status' => DriverStatus::DRIVER_IS_FREE,
                'text' => 'Свободен',
                'color' => '#00E676'
            ],

            1 => [
                'driver_status_id' => 2,
                'name' => 'ON_ACCEPT',
                'status' => DriverStatus::DRIVER_ON_ACCEPT,
                'text' => 'Принял заказ',
                'color' => '#4527A0'
            ],

            2 => [
                'driver_status_id' => 3,
                'name' => 'ON_WAY',
                'status' => DriverStatus::DRIVER_ON_WAY,
                'text' => 'На дароге',
                'color' => '#2979FF'
            ],

            3 => [
                'driver_status_id' => 4,
                'name' => 'IN_PLACE',
                'status' => DriverStatus::DRIVER_IN_PLACE,
                'text' => 'На месте',
                'color' => '#FFEA00'
            ],

            4 => [
                'driver_status_id' => 5,
                'name' => 'IN_ORDER',
                'status' => DriverStatus::DRIVER_IN_ORDER,
                'text' => 'Исполняет заказ',
                'color' => '#C62828'
            ]
        ]);
    }
}
