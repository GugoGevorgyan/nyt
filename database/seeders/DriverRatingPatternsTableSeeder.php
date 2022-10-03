<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DriverRatingPatternsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('driver_rating_patterns')->delete();

        DB::table('driver_rating_patterns')->insert([
            0 =>
                [
                    'alias' => 'fw',
                    'created_at' => null,
                    'description' => 'fwef',
                    'driver_rating_pattern_id' => 1,
                    'name' => 'LARGE_ORDER_REJECTED',
                    'symbol' => 'decrement',
                    'type' => 1,
                    'updated_at' => null,
                    'value' => 1.0,
                ],
            1 =>
                [
                    'alias' => 'we',
                    'created_at' => null,
                    'description' => 'fwef',
                    'driver_rating_pattern_id' => 2,
                    'name' => 'LARGE_ORDER_ACCEPTED',
                    'symbol' => 'increment',
                    'type' => 2,
                    'updated_at' => null,
                    'value' => 2.0,
                ],
            2 =>
                [
                    'alias' => 'wfe',
                    'created_at' => null,
                    'description' => 'fwe',
                    'driver_rating_pattern_id' => 3,
                    'name' => 'NEAR_ORDER_REJECTED',
                    'symbol' => 'decrement',
                    'type' => 3,
                    'updated_at' => null,
                    'value' => 3.0,
                ],
            3 =>
                [
                    'alias' => 'fwe',
                    'created_at' => null,
                    'description' => 'fwe',
                    'driver_rating_pattern_id' => 4,
                    'name' => 'NEAR_ORDER_ACCEPTED',
                    'symbol' => 'increment',
                    'type' => 4,
                    'updated_at' => null,
                    'value' => 1.0,
                ],
            4 =>
                [
                    'alias' => 'wef',
                    'created_at' => null,
                    'description' => 'fwe',
                    'driver_rating_pattern_id' => 5,
                    'name' => 'FAVORITE_DRIVER_ORDER_REJECTED',
                    'symbol' => 'decrement',
                    'type' => 5,
                    'updated_at' => null,
                    'value' => 1.0,
                ],
            5 =>
                [
                    'alias' => 'fwe',
                    'created_at' => null,
                    'description' => 'few',
                    'driver_rating_pattern_id' => 6,
                    'name' => 'FAVORITE_DRIVER_ORDER_ACCEPTED',
                    'symbol' => 'increment',
                    'type' => 6,
                    'updated_at' => null,
                    'value' => 1.0,
                ],
            6 =>
                [
                    'alias' => 'fewfsss',
                    'created_at' => null,
                    'description' => 'fewfsss',
                    'driver_rating_pattern_id' => 7,
                    'name' => 'RESET_AFTER_ACCEPT',
                    'symbol' => 'decrement',
                    'type' => 7,
                    'updated_at' => null,
                    'value' => 6.0,
                ],
            7 =>
                [
                    'alias' => 'wex',
                    'created_at' => null,
                    'description' => 'fwex',
                    'driver_rating_pattern_id' => 8,
                    'name' => 'ATTACH_ORDER_REJECTED',
                    'symbol' => 'decrement',
                    'type' => 8,
                    'updated_at' => null,
                    'value' => 2.0,
                ],
            8 =>
                [
                    'alias' => 'fwex',
                    'created_at' => null,
                    'description' => 'fewx',
                    'driver_rating_pattern_id' => 9,
                    'name' => 'ATTACH_ORDER_ACCEPTED',
                    'symbol' => 'increment',
                    'type' => 9,
                    'updated_at' => null,
                    'value' => 1.0,
                ],
            9 =>
                [
                    'alias' => 'fwefwe',
                    'created_at' => null,
                    'description' => 'ewfew',
                    'driver_rating_pattern_id' => 10,
                    'name' => 'COMMON_LIST_ACCEPTED',
                    'symbol' => 'increment',
                    'type' => 10,
                    'updated_at' => null,
                    'value' => 1.0,
                ],
            10 =>
                [
                    'alias' => 'fewf',
                    'created_at' => null,
                    'description' => 'fewf',
                    'driver_rating_pattern_id' => 11,
                    'name' => 'COMMON_LIST_REJECTED',
                    'symbol' => 'decrement',
                    'type' => 11,
                    'updated_at' => null,
                    'value' => 0.0,
                ],
            11 =>
                [
                    'alias' => 'fewfsss',
                    'created_at' => null,
                    'description' => 'fewfserwf',
                    'driver_rating_pattern_id' => 12,
                    'name' => 'ADDRESS_ORDER_ACCEPTED',
                    'symbol' => 'increment',
                    'type' => 12,
                    'updated_at' => null,
                    'value' => 0.0,
                ],
            12 =>
                [
                    'alias' => 'fewfsss',
                    'created_at' => null,
                    'description' => 'fewfserwf',
                    'driver_rating_pattern_id' => 13,
                    'name' => 'ADDRESS_ORDER_REJECTED',
                    'symbol' => 'decrement',
                    'type' => 13,
                    'updated_at' => null,
                    'value' => 0.0,
                ],
            13 =>
                [
                    'alias' => 'fewfsss',
                    'created_at' => null,
                    'description' => 'fewfserwf',
                    'driver_rating_pattern_id' => 14,
                    'name' => 'PREORDER_REJECTED',
                    'symbol' => 'decrement',
                    'type' => 14,
                    'updated_at' => null,
                    'value' => 0.0,
                ],
        ]);
    }
}
