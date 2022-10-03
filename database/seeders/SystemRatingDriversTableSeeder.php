<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class SystemRatingDriversTableSeeder
 */
class SystemRatingDriversTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws JsonException
     */
    public function run()
    {
        DB::table('system_rating_drivers')->delete();

        DB::table('system_rating_drivers')->insert([

            0 =>
                [
                    'system_rating_driver_id' => 1,
                    'driver_rating_system_pattern_ids' => json_encode(['id' => [1, 2]], JSON_THROW_ON_ERROR, 512),
                ],

            1 =>
                [
                    'system_rating_driver_id' => 2,
                    'driver_rating_system_pattern_ids' => json_encode(['id' => [1, 2]], JSON_THROW_ON_ERROR, 512),
                    'added_rating' => 6,
                    'remove_rating' => 2,
                ],
        ]);
    }
}
