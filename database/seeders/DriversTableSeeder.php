<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Car\Car;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverStatus;

/**
 * Class DriversTableSeeder
 */
class DriversTableSeeder extends Seeder
{

    /**
     * @param  Faker  $faker
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        DB::table('drivers')->delete();

        $statuses = DriverStatus::get();
        $cars = Car::get();

        for ($x = 0; $x < 7; $x++) {
            $car = $cars[random_int(0, count($cars) - 1)];
            $car->load('drivers');
            $car_id = $faker->boolean(90) && count($car->drivers) < 2 ? $car->car_id : null;

            $driver = Driver::create(
                [
                    'driver_info_id' => $x + 1,
                    'created_at' => Carbon::now(),
                    'selected_class' => ['ids' => [random_int(1, 4)]],
                    'nickname' => $faker->userName,
                    'password' => 'secret',
                    'phone' => random_int(111111111111, 999999999999),
                    'current_franchise_id' => $car->franchise_id,
                    'current_status_id' => $statuses[random_int(0, count($statuses) - 1)]->driver_status_id,
                    'lat' => '55.'.$faker->randomNumber(6),
                    'lut' => '37.'.$faker->randomNumber(6),
                    'azimuth' => random_int(0, 360),
                    'rating' => $faker->numberBetween(50, 950),
                    'mean_assessment' => $faker->numberBetween(0.2, 4.9),
                    'updated_at' => Carbon::now(),
                    'car_id' => $car_id,
                    'online' => $car_id ? true : false,
                    'is_ready' => $car_id ? true : false,
                ]
            );

            $driver->cash()->create();

            if ($car_id) {
                $car->update(['current_driver_id' => $driver->driver_id]);
            }
        }
    }
}
