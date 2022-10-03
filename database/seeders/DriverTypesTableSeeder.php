<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

/**
 * Class DriverTypesTableSeeder
 */
class DriverTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('driver_types')->delete();

        DB::table('driver_types')->insert(
            [
                0 =>
                    [
                        'description' => 'Driver arendator. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                        'driver_type_id' => 1,
                        'type' => 'арендатор',
                        'name' => 'messages.driver_type_tenant',
                        'image' => '/storage/seeders/driver-types/tenant.jpg',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                1 =>
                    [
                        'description' => 'Driver ekipag. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                        'driver_type_id' => 2,
                        'type' => 'агрегатор',
                        'name' => 'messages.driver_type_aggregate',
                        'image' => '/storage/seeders/driver-types/crew.jpg',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],

                2 =>
                    [
                        'description' => 'Driver naemnik. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                        'driver_type_id' => 3,
                        'type' => 'расскат',
                        'name' => 'messages.driver_type_roll',
                        'image' => '/storage/seeders/driver-types/mercenary.jpg',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                3 =>
                    [
                        'description' => 'Driver corporative. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                        'driver_type_id' => 4,
                        'type' => 'корпоративный',
                        'name' => 'messages.driver_type_corporate',
                        'image' => '/storage/seeders/driver-types/corporate.jpg',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
            ]
        );
    }
}
