<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TariffAltTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tariff_alt')->delete();

        \DB::table('tariff_alt')->insert(array (
            0 =>
            array (
                'tariff_alt_id' => 1,
                'tariff_id' => 75,
                'alt_id' => 22,
            ),
            1 =>
            array (
                'tariff_alt_id' => 2,
                'tariff_id' => 75,
                'alt_id' => 24,
            ),
        ));


    }
}
