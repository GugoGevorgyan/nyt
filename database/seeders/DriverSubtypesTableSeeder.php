<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Driver\DriverSubtype;

class DriverSubtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DriverSubtype::create([
            'driver_type_id' => 1,
            'name' => 'ИП',
            'value' => 'tenant_individual_entrepreneur'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 1,
            'name' => 'СМОЗАНЯТЫЙ',
            'value' => 'tenant_self_employed'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 1,
            'name' => 'БЕЗ ИП',
            'value' => 'tenant_without_sole_proprietorship'
        ]);

        DriverSubtype::create([
            'driver_type_id' => 2,
            'name' => 'ИП',
            'value' => 'aggregator_individual_entrepreneur'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 2,
            'name' => 'СМОЗАНЯТЫЙ',
            'value' => 'aggregator_self_employed'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 2,
            'name' => 'БЕЗ ИП',
            'value' => 'aggregator_without_sole_proprietorship'
        ]);

        DriverSubtype::create([
            'driver_type_id' => 3,
            'name' => 'ИП',
            'value' => 'will_tell_individual_entrepreneur'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 3,
            'name' => 'СМОЗАНЯТЫЙ',
            'value' => 'will_tell_self_employed'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 3,
            'name' => 'БЕЗ ИП',
            'value' => 'will_tell_without_sole_proprietorship'
        ]);

        DriverSubtype::create([
            'driver_type_id' => 4,
            'name' => 'ИП',
            'value' => 'corporate_individual_entrepreneur'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 4,
            'name' => 'СМОЗАНЯТЫЙ',
            'value' => 'corporate_self_employed'
        ]);
        DriverSubtype::create([
            'driver_type_id' => 4,
            'name' => 'БЕЗ ИП',
            'value' => 'corporate_without_sole_proprietorship'
        ]);
    }
}
