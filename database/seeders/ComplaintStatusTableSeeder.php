<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ComplaintStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('complaint_statuses')->delete();

        DB::table('complaint_statuses')->insert(array(
            0 => [
                'text' => 'Новая',
                'value' => 1,
                'color' => '#C62828'
            ],
            1 => [
                'text' => 'Рассмотрена',
                'value' => 2,
                'color' => '#00C853'
            ],
            2 => [
                'text' => 'На рассмотрении',
                'value' => 3,
                'color' => '#FF6F00'
            ],
        ));
    }
}
