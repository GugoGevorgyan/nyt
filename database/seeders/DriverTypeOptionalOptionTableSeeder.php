<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

/**
 * Class DriverTypeOptionalOptionTableSeeder
 */
class DriverTypeOptionalOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // franchise 1

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 1,
            'driver_type_optional_id' => 1,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 1,
            'driver_type_optional_id' => 2,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 1,
            'driver_type_optional_id' => 3,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 2,
            'driver_type_optional_id' => 1,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 2,
            'driver_type_optional_id' => 2,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 3,
            'driver_type_optional_id' => 2,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 3,
            'driver_type_optional_id' => 3,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 4,
            'driver_type_optional_id' => 1,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 4,
            'driver_type_optional_id' => 2,
            'created_at' => Carbon::now()
        ]);

        DB::table('driver_type_option')->insert([
            'franchise_id' => 1,
            'driver_type_id' => 4,
            'driver_type_optional_id' => 3,
            'created_at' => Carbon::now()
        ]);
    }
}
