<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Exception;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Car\CarStatus;
use Src\Models\Park;
use Str;

/**
 * Class CarsTableSeeder
 */
class CarsTableSeeder extends Seeder
{

    protected $cars = [
        'Audi',
        'BMW',
        'Citroen',
        'Ferrari',
        'Fiat',
        'Ford',
        'Hyundai',
        'Jaguar',
        'Lada',
        'Mazda',
        'Mercedes',
        'Mitsubishi',
        'Nissan',
        'Opel',
        'Peugeot',
        'Renault',
        'Rover',
        'Toyota',
        'Volvo',
        'Trabant',
        'Volkswagen'
    ];

    /**
     * @param  Faker  $faker
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        DB::table('cars')->delete();

        $parks = Park::where('franchise_id', '=', 1)->get();
        $statuses = CarStatus::all();

        for ($x = 0; $x < 7; $x++) {
            DB::table('cars')->insert(
                [
                    'class' => json_encode(['ids' => [random_int(1, 4)]], JSON_THROW_ON_ERROR),
                    'status_id' => $statuses[random_int(0, count($statuses) - 1)]->car_status_id,
                    'created_at' => Carbon::now(),
                    'current_driver_id' => null,
                    'vin_code' => Str::random('16'),
                    'body_number' => Str::random('16'),
                    'vehicle_licence_number' => $faker->randomNumber(2).' ОУ №'.$faker->randomNumber(6),
                    'vehicle_licence_date' => Carbon::now()->subMonths(4),
                    'sts_number' => $faker->randomNumber(2).' '.$faker->randomNumber(2).' №'.$faker->randomNumber(4),
                    'pts_number' => $faker->randomNumber(2).' '.$faker->randomNumber(2).' №'.$faker->randomNumber(4),
                    'registration_date' => Carbon::now()->subMonths(4),
                    'color' => $faker->colorName,
                    'mark' => $this->cars[random_int(0, count($this->cars) - 1)],
                    'model' => Str::random('1').' '.$faker->randomNumber(3),
                    'park_id' => $parks[random_int(0, count($parks) - 1)]->park_id,
                    'franchise_id' => 1,
                    'updated_at' => Carbon::now(),
                    'year' => Carbon::now()->year,
                    'inspection_date' => Carbon::now()->subMonths(random_int(5, 10)),
                    'inspection_expiration_date' => Carbon::now()->addDays(random_int(0, 100)),
                    'inspection_scan' => '/storage/traffic-safety/inspection/inspection_scan.png',
                    'insurance_date' => Carbon::now()->subMonths(random_int(5, 10)),
                    'insurance_expiration_date' => Carbon::now()->addDays(random_int(0, 100)),
                    'insurance_scan' => '/storage/traffic-safety/insurance/insurance_scan.png',
                    'speedometer' => $faker->randomNumber(6),
                    'garage_number' => $faker->randomNumber(4),
                    'state_license_plate' => $faker->randomNumber(2).' '.Str::random('2').' '.$faker->randomNumber(3),
                ]
            );
        }
    }
}
