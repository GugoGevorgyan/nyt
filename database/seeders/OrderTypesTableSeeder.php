<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class OrderTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_types')->insert(array(
            0 =>
                [
                    'name' => 'Client order',
                    'text' => 'Обычный'
                ],
            1 =>
                [
                    'name' => 'Client order by company',
                    'text' => 'Создан компанией'
                ],
            2 =>
                [
                    'name' => 'Company to client',
                    'text' => 'За счет компании'
                ],
            3 =>
                [
                    'name' => 'To other',
                    'text' => 'Другое'
                ]
        ));
    }
}
