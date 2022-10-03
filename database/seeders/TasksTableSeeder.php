<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tasks')->delete();
        
        \DB::table('tasks')->insert(array (
            0 => 
            array (
                'task_id' => 1,
                'command' => 'trafficSafety:check',
                'every' => 'daily',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
            1 => 
            array (
                'task_id' => 2,
                'command' => 'horizon:snapshot',
                'every' => 'daily',
                'params' => NULL,
                'status' => 0,
                'created_at' => '2021-01-26 21:51:50',
            ),
            2 => 
            array (
                'task_id' => 3,
                'command' => 'geoip:update',
                'every' => 'daily',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
            3 => 
            array (
                'task_id' => 4,
                'command' => 'monitor:address-writer',
                'every' => 'hourly',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
            4 => 
            array (
                'task_id' => 5,
                'command' => 'snapshot:create',
                'every' => 'daily',
                'params' => NULL,
                'status' => 0,
                'created_at' => '2021-01-26 21:51:50',
            ),
            5 => 
            array (
                'task_id' => 6,
                'command' => 'drivers_locked:check',
                'every' => 'everyMinute',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
            6 => 
            array (
                'task_id' => 7,
                'command' => 'driver:waybill_calculate',
                'every' => 'twiceDaily',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
            7 => 
            array (
                'task_id' => 8,
                'command' => 'preorder:timeout',
                'every' => 'everyFiveMinutes',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
            8 => 
            array (
                'task_id' => 9,
                'command' => 'telescope:clear',
                'every' => 'hourly',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
            9 => 
            array (
                'task_id' => 10,
                'command' => 'cord:cleaner',
                'every' => 'daily',
                'params' => NULL,
                'status' => 1,
                'created_at' => '2021-01-26 21:51:50',
            ),
        ));
        
        
    }
}