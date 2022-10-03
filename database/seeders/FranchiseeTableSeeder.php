<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FranchiseeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('franchisee')->delete();

        \DB::table('franchisee')->insert(array(
            0 =>
                array(
                    'franchise_id' => 1,
                    'entity_id' => 1,
                    'country_id' => 182,
                    'logo' => '/storage/seeders/franchise/logo.png',
                    'name' => 'Cruickshank, Russel and Reilly',
                    'address' => '5111 Jonas Stream
Janisshire, ME 20173',
                    'zip_code' => '7476',
                    'phone' => '27362223377537',
                    'email' => 'telly.keeling@howell.org',
                    'text' => null,
                    'deleted_at' => null,
                    'created_at' => '2021-10-22 10:48:17.327536',
                    'updated_at' => '2021-10-22 10:48:17.329388',
                ),
        ));
    }
}
