<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Client\Client;
use Src\Models\Corporate\AdminCorporate;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\SystemUsers\WorkerOperator;

/**
 * Class OrdersTableSeeder
 */
class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Faker  $faker
     * @return void
     * @throws JsonException
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        $clients = Client::with(
            [
                'corporateCompanies' => function ($q) {
                    return $q->where('franchise_id', '=', 1)->with('corporateAdmins');
                }
            ]
        )->get();

        $operators = WorkerOperator::whereHas(
            'system_worker',
            function ($q) {
                return $q->where('franchise_id', '=', 1);
            }
        )->with('system_worker')->get();

        foreach ($clients as $iValue) {
            $operator = count($operators) > 1 ? $operators[random_int(0, count($operators) - 1)] : $operators[0];
            $type_id = random_int(1, 4);

            if (2 === $type_id || 3 === $type_id) {
                $payment_type_id = 2;
            } else {
                $array = [1, 3];
                $k = array_rand($array);
                $payment_type_id = $array[$k];
            }

            $in_orders = [1, 2, 3];
            $out_orders = [4, 5];

            /*operator order*/
            DB::table('orders')->insert(
                [
                    'franchisee' => json_encode(
                        ['ids' => [$operator->system_worker->franchise_id]],
                        JSON_THROW_ON_ERROR
                    ),
                    'client_id' => $iValue->client_id,
                    'customer_id' => $operator->system_worker_id,
                    'customer_type' => (new SystemWorker())->getMap(),
                    'operator_id' => $operator->system_worker_id,
                    'car_class_id' => 3,
                    'order_type_id' => $type_id,
                    'payment_type_id' => $payment_type_id,
                    'car_option' => json_encode(
                        [
                            'ids' => [
                                4
                            ]
                        ],
                        JSON_THROW_ON_ERROR,
                        512
                    ),
                    'from_coordinates' => '{"lat": 55.'.$faker->randomNumber(6).', "lut": 37.'.$faker->randomNumber(
                            6
                        ).'}',
                    'to_coordinates' => '{"lat": 55.'.$faker->randomNumber(6).', "lut": 37.'.$faker->randomNumber(
                            6
                        ).'}',
                    'address_from' => $faker->address,
                    'platform' => 'desktop',
                    'status_id' => $iValue->in_order ? $in_orders[random_int(
                        0,
                        count($in_orders) - 1
                    )] : $out_orders[random_int(
                        0,
                        count(
                            $out_orders
                        ) - 1
                    )],
                    'comments' => $faker->text(),
                    'created_at' => now()
                ]
            );

            /*client order*/
            DB::table('orders')->insert(
                [
                    'franchisee' => json_encode(['ids' => [1]], JSON_THROW_ON_ERROR),
                    'client_id' => $iValue->client_id,
                    'customer_id' => $iValue->client_id,
                    'customer_type' => (new Client())->getMap(),
                    'car_class_id' => 3,
                    'order_type_id' => $type_id,
                    'payment_type_id' => $payment_type_id,
                    'car_option' => json_encode(
                        [
                            'ids' => [
                                4
                            ]
                        ],
                        JSON_THROW_ON_ERROR,
                        512
                    ),
                    'from_coordinates' => '{"lat": 55.'.$faker->randomNumber(6).', "lut": 37.'.$faker->randomNumber(
                            6
                        ).'}',
                    'to_coordinates' => '{"lat": 55.'.$faker->randomNumber(6).', "lut": 37.'.$faker->randomNumber(
                            6
                        ).'}',
                    'address_from' => $faker->address,
                    'platform' => 'desktop',
                    'status_id' => $iValue->in_order ? $in_orders[random_int(
                        0,
                        count($in_orders) - 1
                    )] : $out_orders[random_int(
                        0,
                        count(
                            $out_orders
                        ) - 1
                    )],
                    'comments' => $faker->text(),
                    'created_at' => Carbon::now()
                ]
            );

            /*company order*/
            if (count($iValue->corporateCompanies)) {
                $company = $iValue->corporateCompanies[random_int(0, count($iValue->corporateCompanies) - 1)];
                $adminCorporate = $company->corporateAdmins[random_int(0, count($company->corporateAdmins) - 1)];
                DB::table('orders')->insert(
                    [
                        'franchisee' => json_encode(['ids' => [$company->franchise_id]], JSON_THROW_ON_ERROR),
                        'client_id' => $iValue->client_id,
                        'customer_id' => $adminCorporate->admin_corporate_id,
                        'customer_type' => (new AdminCorporate())->getMap(),
                        'car_class_id' => 3,
                        'order_type_id' => 3,
                        'payment_type_id' => 2,
                        'car_option' => json_encode(
                            [
                                'ids' => [
                                    4
                                ]
                            ],
                            JSON_THROW_ON_ERROR,
                            512
                        ),
                        'from_coordinates' => '{"lat": 55.'.$faker->randomNumber(6).', "lut": 37.'.$faker->randomNumber(
                                6
                            ).'}',
                        'to_coordinates' => '{"lat": 55.'.$faker->randomNumber(6).', "lut": 37.'.$faker->randomNumber(
                                6
                            ).'}',
                        'address_from' => $faker->address,
                        'platform' => 'desktop',
                        'status_id' => $iValue->in_order ? $in_orders[random_int(
                            0,
                            count($in_orders) - 1
                        )] : $out_orders[random_int(0, count($out_orders) - 1)],
                        'comments' => $faker->text(200),
                        'created_at' => Carbon::now()
                    ]
                );
            }
        }
    }
}
