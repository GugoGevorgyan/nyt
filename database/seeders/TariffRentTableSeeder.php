<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 *
 */
class TariffRentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        \DB::table('tariff_rents')->delete();

        \DB::table('tariff_rents')->insert([
            0 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 4,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 75,
                    'tariff_rent_id' => 1,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            1 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 6,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 76,
                    'tariff_rent_id' => 2,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            2 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 8,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 77,
                    'tariff_rent_id' => 3,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            3 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 10,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 78,
                    'tariff_rent_id' => 4,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            4 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 12,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 79,
                    'tariff_rent_id' => 5,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            5 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 4,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 80,
                    'tariff_rent_id' => 6,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            6 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 6,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 81,
                    'tariff_rent_id' => 7,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            7 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 8,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 82,
                    'tariff_rent_id' => 8,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            8 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 10,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 83,
                    'tariff_rent_id' => 9,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
            9 =>
                [
                    'area_id' => 24,
                    'cancel_fee' => 0,
                    'created_at' => null,
                    'hours' => 12,
                    'sit_fix_price' => '0.00',
                    'sit_price_km' => '0.00',
                    'sit_price_minute' => '0.00',
                    'sitting_fee' => 0,
                    'tariff_id' => 84,
                    'tariff_rent_id' => 10,
                    'updated_at' => null,
                    'zone_distance' => 5,
                ],
        ]);
    }
}
