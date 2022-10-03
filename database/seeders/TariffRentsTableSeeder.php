<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TariffRentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tariff_rents')->delete();
        
        \DB::table('tariff_rents')->insert(array (
            0 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 4,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 75,
                'tariff_rent_id' => 1,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            1 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 6,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 76,
                'tariff_rent_id' => 2,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            2 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 8,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 77,
                'tariff_rent_id' => 3,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            3 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 10,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 78,
                'tariff_rent_id' => 4,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            4 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 12,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 79,
                'tariff_rent_id' => 5,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            5 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 4,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 80,
                'tariff_rent_id' => 6,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            6 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 6,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 81,
                'tariff_rent_id' => 7,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            7 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 8,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 82,
                'tariff_rent_id' => 8,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            8 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 10,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 83,
                'tariff_rent_id' => 9,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            9 => 
            array (
                'area_id' => 1,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 12,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 84,
                'tariff_rent_id' => 10,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            10 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 4,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 87,
                'tariff_rent_id' => 11,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            11 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 8,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 88,
                'tariff_rent_id' => 12,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            12 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 4,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 89,
                'tariff_rent_id' => 13,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            13 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 8,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 90,
                'tariff_rent_id' => 14,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            14 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 4,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 91,
                'tariff_rent_id' => 15,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            15 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 8,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 92,
                'tariff_rent_id' => 16,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            16 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 4,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 93,
                'tariff_rent_id' => 17,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
            17 => 
            array (
                'area_id' => 24,
                'cancel_fee' => 0,
                'created_at' => NULL,
                'hours' => 8,
                'price_type_id' => 3,
                'sit_fix_price' => '0.00',
                'sit_price_km' => '0.00',
                'sit_price_minute' => '0.00',
                'sitting_fee' => 0,
                'tariff_id' => 94,
                'tariff_rent_id' => 18,
                'updated_at' => NULL,
                'zone_distance' => 0.0,
            ),
        ));
        
        
    }
}