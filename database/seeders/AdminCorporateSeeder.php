<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Faker\Generator as Faker;
use Hash;
use Illuminate\Database\Seeder;
use Src\Models\Corporate\AdminCorporate;
use Src\Models\Corporate\Company;

/**
 * Class AdminCorporateSeeder
 */
class AdminCorporateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker): void
    {
        DB::table('admin_corporates')->delete();

        $companies = Company::get();

        foreach ($companies as $i_value) {
            AdminCorporate::create([
                'franchise_id' => $i_value->franchise_id,
                'company_id' => $i_value->company_id,
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'patronymic' => $faker->firstName,
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'password' => Hash::make('secret'),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
