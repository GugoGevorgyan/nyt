<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Client\Client;
use Src\Models\Corporate\Company;
use Src\Models\Corporate\CorporateClient;

/**
 * Class CorporateClientTableSeeder
 */
class CorporateClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Faker  $faker
     * @return void
     * @throws JsonException
     */
    public function run(Faker $faker): void
    {
        DB::table('corporate_clients')->delete();

        $companies = Company::with('phones')->get();

        foreach ($companies as $iValue) {
            $clients = Client::inRandomOrder()->limit(5)->get();
            foreach ($clients as $xValue) {
                $corporateClient = CorporateClient::create(
                    [
                        'client_id' => $xValue->client_id,
                        'company_id' => $iValue->company_id,
                        'limit' => '6000',
                        'car_classes_ids' => decode(
                            [
                                'ids' => [
                                    1,
                                    2,
                                    3
                                ]
                            ]
                        )
                    ]
                );
//                for ($y = 0; $y < 2; $y++) {
//                    Order::create([
//                        'franchisee' => ['ids' => [$iValue->franchise_id]],
//                        'orderable_id' => $iValue->company_id,
//                        'orderable_type' => Company::class,
//                        'orderable_phone' => $iValue->phones[0]->number,
//                        'passenger_id' => $xValue->client_id,
//                        'car_class_id' => $corporateClient->car_classes_ids['ids'][0],
//                        'order_type_id' => 3,
//                        'payment_type_id' => 2,
//                        'status_id' => 4,
//                        'comment' => $faker->text,
//                        'comment_for_driver' => $faker->text,
//                        'order_create_time' => Carbon::now()->subDays(20 - $y),
//                        'order_time' => Carbon::now()->subDays(20 - $y),
//                        'order_start_time' => Carbon::now()->subDays(20 - $y),
//                        'order_end_time' => Carbon::now()->subDays(20 - $y),
//                        'created_at' => Carbon::now()->subDays(20 - $y),
//                    ]);
//                }
            }
        }
    }
}
