<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Client\Client;
use Src\Models\Order\CanceledOrder;
use Src\Models\Order\Order;

class CanceledOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::where('status_id', '=', 5)->get();

        foreach ($orders as $order) {
            CanceledOrder::create([
                'order_id' => $order->order_id,
                'cancelable_id' => $order->client_id,
                'cancelable_type' => (new Client())->getMap(),
            ]);
        }
    }
}
