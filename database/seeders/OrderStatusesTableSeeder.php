<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class OrderStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_statuses')->delete();

        DB::table('order_statuses')->insert(array(
            0 =>
                array(
                    'order_status_id' => 1,
                    'status' => 1,
                    'name' => 'ORDER_STATUS_PENDING',
                    'text' => 'В ожидании',
                    'color' => '#FF6F00',
                ),
            1 =>
                array(
                    'order_status_id' => 2,
                    'status' => 2,
                    'name' => 'ORDER_STATUS_IN_PROCESS',
                    'text' => 'В процессе',
                    'color' => '#1E88E5',
                ),
            2 =>
                array(
                    'order_status_id' => 3,
                    'status' => 3,
                    'name' => 'ORDER_STATUS_PAUSED',
                    'text' => 'Приостоновлен',
                    'color' => '#18FFFF',
                ),
            3 =>
                array(
                    'order_status_id' => 4,
                    'status' => 4,
                    'name' => 'ORDER_STATUS_COMPLETED',
                    'text' => 'Завершен',
                    'color' => '#00E676',
                ),
            4 =>
                array(
                    'order_status_id' => 5,
                    'status' => 5,
                    'name' => 'ORDER_STATUS_CANCELED',
                    'text' => 'Отменен',
                    'color' => '#C62828',
                ),
        ));
    }
}
