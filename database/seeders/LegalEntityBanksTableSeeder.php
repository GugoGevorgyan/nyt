<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\LegalEntity\LegalEntity;
use Src\Models\LegalEntity\LegalEntityBank;

/**
 * Class LegalEntityBanksTableSeeder
 */
class LegalEntityBanksTableSeeder extends Seeder
{
    /**
     * @param  Faker  $faker
     */
    public function run(Faker $faker): void
    {
        $entities = LegalEntity::get();

        foreach ($entities as $iValue) {
            LegalEntityBank::create([
                'entity_id' => $iValue->entity_id,
                'country_id' => 182,
                'region_id' => 1901,
                'city_id' => 99972,
                'name' => $faker->company.' Bank',
                'bank_account_number' => $faker->bankAccountNumber,
                'correspondent_account_number' => $faker->bankAccountNumber,
                'bank_identification_account' => $faker->randomNumber(9)
            ]);
        }
    }
}
