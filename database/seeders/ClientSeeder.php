<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Faker\Generator as Faker;
use Hash;
use Illuminate\Database\Seeder;

/**
 * Class ClientTypeSeeder
 */
class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        DB::table('clients')->delete();

        for ($i = 0; $i < 5; $i++) {
            DB::table('clients')->insert([
                'phone' => random_int(11111111111, 99999999999),
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'patronymic' => $faker->firstName,
                'email' => $faker->email,
                'online' => $faker->boolean,
                'in_order' => $faker->boolean,
                'logged' => $faker->boolean,
                'password' => Hash::make('secret'),
            ]);
        }
    }
}
