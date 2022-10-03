<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Src\Models\Order\Order;

class OrderStagesCordTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::whereHas('current_shipped')->with(['current_shipped', 'driver', 'completed'])->get();

        foreach ($orders as $order) {
            $data = [
                'accept' => ['lat' => $order->driver->lat, 'lut' => $order->driver->lut],
                'accepted' => Carbon::now(),
                'on_way' => ['lat' => $order->driver->lat, 'lut' => $order->driver->lut],
                'on_wayed' => Carbon::now()->addSeconds(30),
                'in_place' => $order->from_coordinates,
                'in_placed' => Carbon::now()->addMinutes(2),
                'start' => $order->from_coordinates,
                'started' => Carbon::now()->addMinutes(2)
            ];

            if ($order->completed) {
                $data['end'] = ['lat' => $order->to_coordinates['lat'] + 0.01, 'lut' => $order->to_coordinates['lut'] + 0.01];
                $data['ended'] = Carbon::now()->addMinutes(rand(5, 15));
            }

            $order->stage()->create($data);
        }
    }
}
