<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TariffPriceTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('tariff_price_types')->delete();

        DB::table('tariff_price_types')->insert(array(
            0 =>
                array(
                    'created_at' => '2021-03-22 09:31:14',
                    'name' => 'Тариф по минутам',
                    'status' => 1,
                    'tariff_type_id' => 1,
                    'type' => 1,
                ),
            1 =>
                array(
                    'created_at' => '2021-03-22 09:31:14',
                    'name' => 'Тариф по километрам',
                    'status' => 1,
                    'tariff_type_id' => 2,
                    'type' => 2,
                ),
            2 =>
                array(
                    'created_at' => '2021-03-22 09:31:14',
                    'name' => 'Тариф по минутам и километрам',
                    'status' => 1,
                    'tariff_type_id' => 3,
                    'type' => 3,
                ),
            3 =>
                array(
                    'created_at' => '2021-03-22 09:31:14',
                    'name' => 'Фиксированная цена (Тариф из зоны А в зону Б)',
                    'status' => 1,
                    'tariff_type_id' => 4,
                    'type' => 4,
                ),
            4 =>
                array(
                    'created_at' => '2021-03-22 09:31:14',
                    'name' => 'Аренда автомобиля (с водителем )',
                    'status' => 1,
                    'tariff_type_id' => 5,
                    'type' => 5,
                ),
        ));
    }
}
