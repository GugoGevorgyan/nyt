<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderProcess;

class OrderProcessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::whereHas('current_shipped')
            ->with(['current_shipped', 'in_process_road'])
            ->get();

        foreach ($orders as $order) {
            OrderProcess::create([
                'order_shipped_id' => $order->current_shipped->order_shipped_driver_id,
                'price' => rand(1000, 5000),
                'sitting_price' => $order->status_id !== 4 ? null : rand(50, 150),
                'pause_price' => $order->status_id !== 4 ? null : rand(50, 150),
                'distance_traveled' => $order->status_id !== 4 ? null : $order->current_shipped->in_process_road->distance,
                'travel_time' => $order->status_id !== 4 ? null : $order->current_shipped->in_process_road->duration,
                'waiting_time' => $order->status_id !== 4 ? null : rand(0, 150),
            ]);
        }
    }
}
