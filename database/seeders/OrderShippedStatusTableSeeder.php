<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderShippedStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_shipped_status')->delete();
        
        \DB::table('order_shipped_status')->insert(array (
            0 => 
            array (
                'order_shipped_status_id' => 1,
                'status' => 1,
                'name' => 'PRE_PENDING',
                'text' => 'Ожидание',
                'color' => '#FF6F00',
                'description' => 'lorem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'order_shipped_status_id' => 2,
                'status' => 2,
                'name' => 'PRE_STATUS_ACCEPTED',
                'text' => 'Принят',
                'color' => '#00E676',
                'description' => 'lorem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'order_shipped_status_id' => 3,
                'status' => 3,
                'name' => 'PRE_STATUS_REJECTED',
                'text' => 'Отклонен',
                'color' => '#C62828',
                'description' => 'lorem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'order_shipped_status_id' => 4,
                'status' => 4,
                'name' => 'PRE_STATUS_CANCELED',
                'text' => 'Отменен',
                'color' => '#C62828',
                'description' => 'lorem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'order_shipped_status_id' => 5,
                'status' => 5,
                'name' => 'PRE_MANUAL',
                'text' => 'Manual',
                'color' => '#C62828',
                'description' => 'lorem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'order_shipped_status_id' => 6,
                'status' => 6,
                'name' => 'PRE_UNPIN',
                'text' => 'UNPINED',
                'color' => '#C62828',
                'description' => 'lorem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}