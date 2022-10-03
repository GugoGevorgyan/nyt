<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DriverTypeOptionalsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('driver_type_optionals')->delete();
        
        \DB::table('driver_type_optionals')->insert(array (
            0 => 
            array (
                'created_at' => '2021-10-22 14:21:30.344216',
                'description' => 'Show from',
                'driver_type_optional_id' => 1,
                'name' => 'show_from',
                'updated_at' => '2021-10-22 14:21:30.344733',
                'valued' => 0,
            ),
            1 => 
            array (
                'created_at' => '2021-10-22 14:21:30.349185',
                'description' => 'Show to',
                'driver_type_optional_id' => 2,
                'name' => 'show_to',
                'updated_at' => '2021-10-22 14:21:30.349463',
                'valued' => 0,
            ),
            2 => 
            array (
                'created_at' => '2021-10-22 14:21:30.353884',
                'description' => 'Show price',
                'driver_type_optional_id' => 3,
                'name' => 'show_price',
                'updated_at' => '2021-10-22 14:21:30.354171',
                'valued' => 0,
            ),
            3 => 
            array (
                'created_at' => '2021-10-22 14:21:30.356647',
                'description' => 'Show price',
                'driver_type_optional_id' => 4,
                'name' => 'order_percent',
                'updated_at' => '2021-10-22 14:21:30.356944',
                'valued' => 1,
            ),
            4 => 
            array (
                'created_at' => '2021-10-22 14:21:30.356647',
                'description' => 'Show Company Name',
                'driver_type_optional_id' => 5,
                'name' => 'show_company_name',
                'updated_at' => '2021-10-22 14:21:30.356647',
                'valued' => 0,
            ),
            5 => 
            array (
                'created_at' => '2021-10-22 14:21:30.356647',
                'description' => 'Show Client Name',
                'driver_type_optional_id' => 6,
                'name' => 'show_client_name',
                'updated_at' => '2021-10-22 14:21:30.356647',
                'valued' => 0,
            ),
            6 => 
            array (
                'created_at' => '2021-10-22 14:21:30.356647',
                'description' => 'Show Client phone',
                'driver_type_optional_id' => 7,
                'name' => 'show_client_phone',
                'updated_at' => '2021-10-22 14:21:30.356647',
                'valued' => 0,
            ),
        ));
        
        
    }
}