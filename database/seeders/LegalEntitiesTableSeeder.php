<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\LegalEntity\LegalEntity;

class LegalEntitiesTableSeeder extends Seeder
{
    /**
     * @param  Faker  $faker
     */
    public function run(Faker $faker)
    {
        for ($x = 0; $x < 2; $x++) {
            LegalEntity::create(
                [
                    'name' => $faker->company,
                    'type_id' => 1,
                    'country_id' => 182,
                    'region_id' => 1879,
                    'city_id' => 101074,
                    'zip_code' => $faker->randomNumber(4),
                    'address' => $faker->address,
                    'phone' => $faker->phoneNumber,
                    'email' => $faker->companyEmail,
                    'tax_inn' => $faker->randomNumber(9),
                    'tax_kpp' => $faker->randomNumber(9),
                    'tax_psrn' => $faker->randomNumber(9),
                    'tax_psrn_serial' => $faker->randomNumber(2).' № '.$faker->randomNumber(9),
                    'tax_psrn_issued_by' => 'Инспекцией Министерства РФ по налогам и сборам по городу Волоколамск МО',
                    'tax_psrn_date' => Carbon::now()->subMonths(6),
                    'aucneb' => $faker->randomNumber(5).','.$faker->randomNumber(5).','.$faker->randomNumber(5),
                    'arceo' => $faker->randomNumber(8),
                    'arcfo' => $faker->randomNumber(2),
                    'arclf' => $faker->randomNumber(2),
                    'registration_certificate_number' => $faker->randomNumber(3).','.$faker->randomNumber(3).','.$faker->randomNumber(3),
                    'registration_certificate_date' => Carbon::now()->subMonths(4),
                    'director_name' => $faker->firstName,
                    'director_surname' => $faker->lastName,
                    'director_patronymic' => $faker->firstName,
                ]
            );
        }
    }
}
