<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Src\Models\Order\Order;
use Src\Models\Order\OrderInProcessRoad;

class OrderInProcessRoadsTableSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::whereHas('current_shipped')
            ->with(['current_shipped', 'stage'])
            ->get();

        foreach ($orders as $order) {
            $distance = $order->status_id !== 4 ? null :
                $this->distance($order->stage->in_place['lat'], $order->stage->in_place['lut'], $order->stage->end['lat'], $order->stage->end['lut']);

            OrderInProcessRoad::create([
                'shipment_driver_id' => $order->current_shipped->order_shipped_driver_id,
                'distance' => 4 !== $order->status_id ? null : $distance,
                'duration' => 4 !== $order->status_id ? null : $distance * 60,
                'selected' => 1,
                'route' => [$order->stage->in_place, $order->to_coordinates],
                'real_road' => $order->status_id !== 4 ? null : $this->realRoad($order)
            ]);
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return $km;
    }

    public function realRoad($order)
    {
        $result = [];
        $result[] = ['lat' => $order->stage->in_place['lat'], 'lut' => $order->stage->in_place['lut'], 'date' => Carbon::now()];
        for ($i = 0; $i < 120; $i++) {
            $result[] = [
                'lat' => bcadd((float)$result[$i]['lat'], 1 / rand(10, 100), 6),
                'lut' => bcadd((float)$result[$i]['lut'], 1 / rand(10, 100), 6),
                'date' => Carbon::now()->addSeconds($i + 1)
            ];
        }
        $result[] = ['lat' => $order->stage->end['lat'], 'lut' => $order->stage->end['lut'], 'date' => Carbon::now()->addSeconds(121)];
        return $result;
    }
}
