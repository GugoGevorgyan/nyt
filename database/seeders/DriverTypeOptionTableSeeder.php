<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DriverTypeOptionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('driver_type_option')->delete();
    }
}
