<?php

declare(strict_types=1);

namespace Database\Seeders;

use Exception;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Franchise\Franchise;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class SystemWorkersTableSeeder
 */
class SystemWorkersTableSeeder extends Seeder
{

    /**
     * @param Faker $faker
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        $franchises = Franchise::get();

        foreach ($franchises as $franchise) {
            SystemWorker::create(
                [
                    'franchise_id' => $franchise->franchise_id,
                    'is_admin' => 1,
                    'name' => $faker->firstName,
                    'surname' => $faker->lastName,
                    'patronymic' => $faker->firstName,
                    'nickname' => 'admin'.$franchise->franchise_id,
                    'email' => 'admin'.$franchise->franchise_id.'@mail.ru',
                    'password' => 'secret',
                    'phone' => '7'.random_int(10000000, 90000000),
                ]
            );

            if (!app()->environment('production')) {
                for ($x = 0; $x < 10; $x++) {
                    SystemWorker::create(
                        [
                            'franchise_id' => $franchise->franchise_id,
                            'is_admin' => 0,
                            'name' => $faker->firstName,
                            'surname' => $faker->lastName,
                            'patronymic' => $faker->firstName,
                            'nickname' => 'admin'.$franchise->franchise_id.'_'.$x,
                            'email' => 'admin'.$franchise->franchise_id.'_'.$x.'@mail.ru',
                            'password' => 'secret',
                            'phone' => '7'.random_int(10000000, 90000000),
                        ]
                    );
                }
            }
        }
    }
}
