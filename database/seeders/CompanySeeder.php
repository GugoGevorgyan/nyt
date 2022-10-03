<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Src\Models\Corporate\Company;
use Src\Models\Franchise\Franchise;
use Src\Models\LegalEntity\LegalEntity;

/**
 * Class CompanySeeder
 */
class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Faker  $faker
     * @return void
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        DB::table('companies')->delete();

        $franchises = Franchise::get();

        foreach ($franchises as $i_value) {
            $entities = LegalEntity::get();

            for ($x = 0; $x < 4; $x++) {
                Company::create(
                    [
                        'franchise_id' => $i_value->franchise_id,
                        'entity_id' => $entities[random_int(0, count($entities) - 1)]->legal_entity_id,
                        'name' => $faker->company,
                        'email' => $faker->email,
                        'address' => $faker->address,
                        'details' => $faker->text,
                        'order_canceled_timeout' => random_int(1, 20),
                        'period' => random_int(1, 10),
                        'frequency' => random_int(1, 5),
                        'code' => $faker->randomNumber(5),
                        'contract_start' => Carbon::now(),
                        'contract_end' => Carbon::now(),
                        'contract_scan' => '/storage/company-contract-scans/scan.jpg'
                    ]
                );
            }
        }
    }
}
