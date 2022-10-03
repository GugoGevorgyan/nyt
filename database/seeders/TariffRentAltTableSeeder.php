<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TariffRentAltTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tariff_rent_alt')->delete();
        
        \DB::table('tariff_rent_alt')->insert(array (
            0 => 
            array (
                'alt_id' => 16,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-09 19:17:08',
                'rent_id' => 1,
                'tariff_rent_alt_id' => 1,
            ),
            1 => 
            array (
                'alt_id' => 16,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:52:29',
                'rent_id' => 2,
                'tariff_rent_alt_id' => 2,
            ),
            2 => 
            array (
                'alt_id' => 16,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:53:18',
                'rent_id' => 3,
                'tariff_rent_alt_id' => 3,
            ),
            3 => 
            array (
                'alt_id' => 16,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:56:18',
                'rent_id' => 4,
                'tariff_rent_alt_id' => 4,
            ),
            4 => 
            array (
                'alt_id' => 16,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:56:18',
                'rent_id' => 5,
                'tariff_rent_alt_id' => 5,
            ),
            5 => 
            array (
                'alt_id' => 14,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:56:19',
                'rent_id' => 6,
                'tariff_rent_alt_id' => 6,
            ),
            6 => 
            array (
                'alt_id' => 14,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:56:19',
                'rent_id' => 7,
                'tariff_rent_alt_id' => 7,
            ),
            7 => 
            array (
                'alt_id' => 14,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:56:19',
                'rent_id' => 8,
                'tariff_rent_alt_id' => 8,
            ),
            8 => 
            array (
                'alt_id' => 14,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:56:19',
                'rent_id' => 9,
                'tariff_rent_alt_id' => 9,
            ),
            9 => 
            array (
                'alt_id' => 14,
                'alt_type' => 'tariffRegionCity',
                'created_at' => '2021-11-10 12:56:19',
                'rent_id' => 10,
                'tariff_rent_alt_id' => 10,
            ),
        ));
        
        
    }
}