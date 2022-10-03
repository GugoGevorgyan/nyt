<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class DriverGraphicsTableSeeder
 */
class DriverGraphicsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     * @throws JsonException
     */
    public function run(): void
    {
        DB::table('driver_graphics')->delete();

        DB::table('driver_graphics')->insert([
            0 =>
                [
                    'description' => 'Lorem description',
                    'name' => '5 + 2',
                    'weekend_days_count' => 2,
                    'working_days_count' => 5,
                    'week' => json_encode(['values' => [true, true, true, true, true, false, false]], JSON_THROW_ON_ERROR)
                ],
            1 =>
                [
                    'description' => 'Lorem description',
                    'name' => '6 + 1',
                    'weekend_days_count' => 1,
                    'working_days_count' => 6,
                    'week' => json_encode(['values' => [true, true, true, true, true, true, false]], JSON_THROW_ON_ERROR)
                ],
            2 =>
                [
                    'description' => 'Lorem description',
                    'name' => '1 или 7',
                    'weekend_days_count' => 0,
                    'working_days_count' => 7,
                    'week' => json_encode(['values' => [true, true, true, true, true, true, true]], JSON_THROW_ON_ERROR)
                ],
            3 =>
                [
                    'description' => 'Lorem description',
                    'name' => '2 + 2',
                    'weekend_days_count' => 3,
                    'working_days_count' => 4,
                    'week' => json_encode(['values' => [true, true, false, false]], JSON_THROW_ON_ERROR)
                ],
        ]);
    }
}
