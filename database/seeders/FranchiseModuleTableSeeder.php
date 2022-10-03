<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FranchiseModuleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('franchise_module')->delete();

        \DB::table('franchise_module')->insert([
            0 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 1,
                    'module_id' => 1,
                ],
            1 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 2,
                    'module_id' => 2,
                ],
            2 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 3,
                    'module_id' => 3,
                ],
            3 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 4,
                    'module_id' => 4,
                ],
            4 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 5,
                    'module_id' => 5,
                ],
            5 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 6,
                    'module_id' => 6,
                ],
            6 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 7,
                    'module_id' => 7,
                ],
            7 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 8,
                    'module_id' => 8,
                ],
            8 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 9,
                    'module_id' => 9,
                ],
            9 =>
                [
                    'created_at' => '2021-11-26 12:14:31',
                    'franchise_id' => 1,
                    'franchise_module_id' => 10,
                    'module_id' => 10,
                ],
        ]);
    }
}
