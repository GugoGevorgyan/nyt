<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class DriverRatingLevelsTableSeeder
 */
class DriverRatingLevelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('driver_rating_levels')->delete();

        DB::table('driver_rating_levels')->insert(
            [
                0 =>
                    [
                        'driver_rating_level_id' => 1,
                        'level' => 'BADLY',
                        'from' => 0,
                        'before' => 49,
                    ],
                1 =>
                    [
                        'driver_rating_level_id' => 2,
                        'level' => 'LOW',
                        'from' => 50,
                        'before' => 149,
                    ],
                2 =>
                    [
                        'driver_rating_level_id' => 3,
                        'level' => 'BELLOW_AVERAGE',
                        'from' => 150,
                        'before' => 249,
                    ],
                3 =>
                    [
                        'driver_rating_level_id' => 4,
                        'level' => 'AVERAGE',
                        'from' => 250,
                        'before' => 399,
                    ],
                4 =>
                    [
                        'driver_rating_level_id' => 5,
                        'level' => 'ABOVE_AVERAGE',
                        'from' => 400,
                        'before' => 599,
                    ],
                5 =>
                    [
                        'driver_rating_level_id' => 6,
                        'level' => 'OVER',
                        'from' => 600,
                        'before' => 749,
                    ],
                6 =>
                    [
                        'driver_rating_level_id' => 7,
                        'level' => 'OVER_STAR',
                        'from' => 750,
                        'before' => 899,
                    ],
                7 =>
                    [
                        'driver_rating_level_id' => 8,
                        'level' => 'SUPER_STAR',
                        'from' => 900,
                        'before' => 1000,
                    ],
            ]
        );
    }
}
