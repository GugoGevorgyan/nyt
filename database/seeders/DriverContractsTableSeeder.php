<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverContract;
use Src\Models\Driver\DriverSubtype;
use Src\Models\Franchise\FranchiseEntity;
use Src\Models\LegalEntity\LegalEntity;

/**
 * Class DriverContractsTableSeeder
 */
class DriverContractsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Faker  $faker
     * @return void
     */
    public function run(Faker $faker)
    {
        $drivers = Driver::with('car.park')->get();

        for ($i = 0; $i < count($drivers); $i++) {
            $rand1 = rand(3, 7);
            $rand2 = $rand1 - 2;
            $rand3 = rand(2, 6);
            $type_id = rand(1, 4);
            $subtypes = DriverSubtype::where('driver_type_id', '=', $type_id)->get();
            $subtype = $subtypes[rand(0, count($subtypes) - 1)];
            if ($drivers[$i]->car_id) {
                if (
                    $subtype->driver_subtype_id === 1 ||
                    $subtype->driver_subtype_id === 4 ||
                    $subtype->driver_subtype_id === 7 ||
                    $subtype->driver_subtype_id === 10
                ) {
                    $this->makeEntity($drivers[$i], $faker);
                }

                DriverContract::create([
                    'driver_id' => $drivers[$i]->driver_id,
                    'driver_type_id' => $type_id,
                    'driver_subtype_id' => $subtype->driver_subtype_id,
                    'driver_graphic_id' => rand(1, 4),
                    'entity_id' => $drivers[$i]->car->park->entity_id,
                    'car_id' => $drivers[$i]->car_id,
                    'signing_day' => Carbon::now()->subWeeks($rand1),
                    'expiration_day' => Carbon::now()->subWeeks($rand2),
                    'work_start_day' => Carbon::now()->subWeeks($rand1),
                    'duration' => ($rand1 - $rand2) * 7,
                    'active' => false,
                    'signed' => true,
                    'free_days_price' => rand(1000, 2000),
                    'busy_days_price' => rand(2000, 3000),
                    'created_at' => Carbon::now()->subWeeks($rand1),
                    'updated_at' => Carbon::now()->subWeeks($rand1)
                ]);
            }

            DriverContract::create([
                'driver_id' => $drivers[$i]->driver_id,
                'driver_type_id' => $type_id,
                'driver_subtype_id' => $subtype->driver_subtype_id,
                'driver_graphic_id' => rand(1, 4),
                'entity_id' => $drivers[$i]->car && $drivers[$i]->car->park ? $drivers[$i]->park->entity_id : null,
                'car_id' => $drivers[$i]->car ? $drivers[$i]->car->car_id : null,
                'signing_day' => Carbon::now()->subWeeks($rand2),
                'expiration_day' => Carbon::now()->addWeeks($rand3),
                'work_start_day' => Carbon::now()->subWeeks($rand2),
                'duration' => ($rand2 + $rand3) * 7,
                'active' => $drivers[$i]->car ? true : false,
                'signed' => $drivers[$i]->car ? true : false,
                'free_days_price' => rand(1000, 2000),
                'busy_days_price' => rand(2000, 3000),
                'created_at' => Carbon::now()->subWeeks($rand2),
                'updated_at' => Carbon::now()->subWeeks($rand2),
            ]);
        }
    }

    /**
     * @param $driver
     * @param $faker
     */
    protected function makeEntity($driver, $faker)
    {
        $entity = LegalEntity::create(
            [
                'type_id' => 6,
                'name' => 'ИП '.$driver->driver_info->surname.' '.$driver->driver_info->name.' '.$driver->driver_info->patronymic,
                'country_id' => $driver->driver_info->country_id,
                'region_id' => $driver->driver_info->region_id,
                'city_id' => $driver->driver_info->city_id,
                'zip_code' => $driver->driver_info->zip_code,
                'address' => $driver->driver_info->address,
                'phone' => $driver->phone,
                'email' => $driver->driver_info->email,
                'tax_inn' => $faker->randomNumber(9),
                'tax_kpp' => $faker->randomNumber(9),
                'tax_psrn' => $faker->randomNumber(9),
                'tax_psrn_serial' => $faker->randomNumber(2).' № '.$faker->randomNumber(9),
                'tax_psrn_issued_by' => 'Инспекцией Министерства РФ по налогам и сборам по городу Волоколамск МО',
                'tax_psrn_date' => Carbon::now()->subMonths(6),
                'director_name' => $driver->driver_info->name,
                'director_surname' => $driver->driver_info->surname,
                'director_patronymic' => $driver->driver_info->patronymic
            ]
        );
        $driver->update(['entity_id' => $entity->legal_entity_id]);
        FranchiseEntity::create(
            [
                'franchise_id' => $driver->current_franchise_id,
                'legal_entity_id' => $entity->legal_entity_id
            ]
        );
    }
}
