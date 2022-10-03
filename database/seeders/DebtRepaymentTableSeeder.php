<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DebtRepaymentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('debt_repayment')->delete();

        DB::table('debt_repayment')->insert(array(
            0 =>
                array(
                    'amount' => 1,
                    'debt_repayment_id' => 1,
                    'max_debt' => '1000.00',
                    'min_debt' => '0.00',
                ),
            1 =>
                array(
                    'amount' => 5,
                    'debt_repayment_id' => 2,
                    'max_debt' => '5000.00',
                    'min_debt' => '1001.00',
                ),
            2 =>
                array(
                    'amount' => 10,
                    'debt_repayment_id' => 3,
                    'max_debt' => '10000.00',
                    'min_debt' => '5001.00',
                ),
            3 =>
                array(
                    'amount' => 20,
                    'debt_repayment_id' => 4,
                    'max_debt' => '20000.00',
                    'min_debt' => '10001.00',
                ),
            4 =>
                array(
                    'amount' => 30,
                    'debt_repayment_id' => 5,
                    'max_debt' => '50000.00',
                    'min_debt' => '20001.00',
                ),
        ));
    }
}
