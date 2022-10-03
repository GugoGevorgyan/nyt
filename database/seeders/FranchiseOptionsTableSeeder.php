<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class FranchiseOptionsTableSeeder
 */
class FranchiseOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('franchise_options')->delete();

        DB::table('franchise_options')->insert([
            0 =>
                [
                    'created_at' => null,
                    'default_assessment' => '{"1": 2.5, "2": 2, "3": 3, "4": 4.5}',
                    'default_rating' => '{"1": 300, "2": 500, "3": 600, "4": 450}',
                    'waybill_max_days' => '{"1": 7, "2": 7, "3": 7, "4": 7}',
                    'franchise_id' => 1,
                    'franchise_option_id' => 3,
                    'order_cancel_before' => '2',
                    'order_pending_time' => 1,
                    'updated_at' => null,
                ],
        ]);
    }
}
