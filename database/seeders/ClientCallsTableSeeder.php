<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class ClientCallsTableSeeder
 */
class ClientCallsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
//        $orders = Order::where('worker_operator_id', '<>', null)
//            ->with([
//                'franchise' => function ($q) {
//                    return $q->with([
//                        'phones' => function ($q) {
//                            return $q->with('subPhones');
//                        }
//                    ]);
//                },
//                'system_worker'
//            ])
//            ->get();
//
//        for ($i = 0, $iMax = count($orders); $i < $iMax; $i++) {
//            $subPhones = $orders[$i]->franchise->phones[0]->subPhones;
//
//            $count = random_int(1, 3);
//
//            for ($k = 0; $k < $count; $k++) {
//                ClientCall::create([
//                    'franchise_id' => $orders[$i]->franchise_id,
//                    'franchise_phone_id' => $orders[$i]->franchise->phones[0]->franchise_phone_id,
//                    'franchise_sub_phone_id' => $subPhones[random_int(0, count($subPhones) - 1)]->franchise_sub_phone_id,
//                    'system_worker_id' => $orders[$i]->system_worker->system_worker_id,
//                    'worker_operator_id' => $orders[$i]->worker_operator_id,
//                    'callable_id' => $orders[$i]->orderable_id,
//                    'callable_type' => $orders[$i]->orderable_type,
//                    'client_phone' => $orders[$i]->client_phone,
//                    'call_start' => Carbon::now()->format('Y-m-d H:i:s.u'),
//                    'call_end' => Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s.u'),
//                    'call_duration' => 60,
//                    'incoming' => 1 == random_int(0, 1),
//                    'answered' => 1 == random_int(0, 1),
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now()
//                ]);
//            }
//        }
    }
}
