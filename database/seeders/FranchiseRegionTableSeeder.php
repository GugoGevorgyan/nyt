<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FranchiseRegionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('franchise_region')->delete();

        \DB::table('franchise_region')->insert(array (
            0 =>
                array (
                    'created_at' => '2021-11-26 13:45:01',
                    'franchise_id' => 1,
                    'franchise_region_id' => 9,
                    'region_id' => 1882,
                ),
            1 =>
                array (
                    'created_at' => '2021-11-26 13:45:01',
                    'franchise_id' => 1,
                    'franchise_region_id' => 10,
                    'region_id' => 1901,
                ),
        ));


    }
}
