<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Src\Models\LegalEntity\LegalEntityType;

/**
 * Class LegalEntityTypesTableSeeder
 */
class LegalEntityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LegalEntityType::create([
            'entity_type_id' => 1,
            'abbreviation' => 'OOO',
            'name' => 'Общество с ограниченной ответственностью',
            'value' => 'limited_liability_company',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        LegalEntityType::create([
            'entity_type_id' => 2,
            'abbreviation' => 'ПАО',
            'name' => 'Публичное акционерное общество',
            'value' => 'public_joint_stock_company',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        LegalEntityType::create([
            'entity_type_id' => 3,
            'abbreviation' => 'ЗАО',
            'name' => 'Закрытое акционерное общество',
            'value' => 'closed_joint_stock_company',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        LegalEntityType::create([
            'entity_type_id' => 4,
            'abbreviation' => 'ОАО',
            'name' => 'Открытое акционерное общество',
            'value' => 'public_corporation',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        LegalEntityType::create([
            'entity_type_id' => 5,
            'abbreviation' => 'РАО',
            'name' => 'Российское авторское общество',
            'value' => 'russian_copyright_society',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        LegalEntityType::create([
            'entity_type_id' => 6,
            'abbreviation' => 'ИП',
            'name' => 'Индивидуальный предприниматель',
            'value' => 'individual_entrepreneur',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
