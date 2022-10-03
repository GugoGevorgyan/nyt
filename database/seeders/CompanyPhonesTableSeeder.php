<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Corporate\Company;
use Src\Models\Corporate\CompanyPhone;

/**
 * Class CompanyPhonesTableSeeder
 */
class CompanyPhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $companies = Company::with('clients')->get();
        foreach ($companies as $iValue) {
            CompanyPhone::create([
                'company_id' => $iValue->company_id,
                'number' => preg_replace('/[^0-9]/', '', $faker->phoneNumber)
            ]);

            CompanyPhone::create([
                'company_id' => $iValue->company_id,
                'number' => preg_replace('/[^0-9]/', '', $faker->phoneNumber)
            ]);

            foreach ($iValue->clients as $yValue) {
                CompanyPhone::create([
                    'company_id' => $iValue->company_id,
                    'number' => $yValue->phone
                ]);
            }
        }
    }
}
