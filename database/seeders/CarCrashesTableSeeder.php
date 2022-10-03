<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Str;

/**
 * Class CarCrashesTableSeeder
 */
class CarCrashesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('car_crashes')->delete();

        DB::table('car_crashes')->insert(
            [
                0 =>
                    [
                        'car_id' => 1,
                        'driver_id' => 1,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                1 =>
                    [
                        'car_id' => 1,
                        'driver_id' => 2,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                2 =>
                    [
                        'car_id' => 1,
                        'driver_id' => 1,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                3 =>
                    [
                        'car_id' => 2,
                        'driver_id' => 2,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                5 =>
                    [
                        'car_id' => 11,
                        'driver_id' => 11,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                6 =>
                    [
                        'car_id' => 11,
                        'driver_id' => 12,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                7 =>
                    [
                        'car_id' => 11,
                        'driver_id' => 12,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                8 =>
                    [
                        'car_id' => 11,
                        'driver_id' => 1,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                9 =>
                    [
                        'car_id' => 10,
                        'driver_id' => 1,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                10 =>
                    [
                        'car_id' => 10,
                        'driver_id' => 12,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
                11 =>
                    [
                        'car_id' => 1,
                        'driver_id' => 12,
                        'dateTime' => date('Y-m-d H:i:s', time() - rand(10000, 90000000)),
                        'address' => 'Mr John Smith 132, My Street, Bigtown BG23 4YZ England',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosq.',
                        'our_fault' => rand(0, 1),
                        'inspector_info' => 'John Smith +374 '.rand(10000000, 99999999),
                        'participant_info' => 'Dave Doe +374 '.rand(10000000, 99999999),
                        'act' => Str::random('16'),

                    ],
            ]
        );
    }
}
