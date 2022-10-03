<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DebtTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('debt_tpyes')->delete();
        DB::table('debt_types')->insert(array(
            0 =>
                [
                    'name' => 'penalty',
                ],
            1 =>
                [
                    'name' => 'other',
                ]
        ));
    }
}
