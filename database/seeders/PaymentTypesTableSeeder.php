<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class PaymentTypesTableSeeder
 */
class PaymentTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')->delete();

        DB::table('payment_types')->insert(
            [
                0 =>
                    [
                        'name' => 'Наличными',
                        'payment_type_id' => 1,
                        'text' => 'messages.payment_type_cash',
                        'type' => 1,
                        'deleted_at' => null,
                    ],
                1 =>
                    [
                        'name' => 'За счет компании',
                        'payment_type_id' => 2,
                        'text' => 'messages.payment_type_company',
                        'type' => 2,
                        'deleted_at' => null,
                    ],
                2 =>
                    [
                        'name' => 'Кредитной картой',
                        'payment_type_id' => 3,
                        'text' => 'messages.payment_type_card',
                        'type' => 3,
                        'deleted_at' => now(),
                    ],
            ]
        );
    }
}
