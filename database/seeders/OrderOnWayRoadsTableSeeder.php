<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderOnWayRoad;

/**
 * Class OrderOnWayRoadsTableSeeder
 * @package Database\Seeders
 */
class OrderOnWayRoadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $orders = Order::whereHas('current_shipped')->with(['current_shipped', 'stage'])->get();

        foreach ($orders as $order) {
            $distance = $this->distance($order->stage->on_way['lat'], $order->stage->on_way['lut'], $order->stage->in_place['lat'],
                $order->stage->in_place['lut']);

            OrderOnWayRoad::create([
                'shipment_driver_id' => $order->current_shipped->order_shipped_driver_id,
                'distance' => $distance,
                'duration' => $distance,
                'selected' => 1,
                'route' => [$order->stage->on_way, $order->stage->in_place],
                'real_road' => [$order->stage->on_way, $order->stage->in_place]
            ]);
        }
    }

//    public function route($lat1, $lut1, $lat2, $lut2)
//    {
//        $result = [];
//        $newLat = $lat2;
//        $newLut = $lut2;
//
//        while (abs($newLat - $lat1) > 0.1 || abs($newLut - $lut1) > 0.1) {
//            if (abs($newLat - $lat1) > 0.1 ){
//                if ($newLat > $lat1){
//                    $newLat = $newLat - 0.01;
//                }else{
//                    $newLat = $newLat + 0.01;
//                }
//
//            }
//            if (abs($newLut - $lut1) > 0.1 ){
//                if ($newLut > $lut1){
//                    $newLut = $newLut - 0.01;
//                }else{
//                    $newLut = $newLut + 0.01;
//                }
//
//            }
//
//            $result[] = ['lat' => $newLat, 'lut' => $newLut];
//        }
//
//        return $result;
//    }

    /**
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @return float|int
     */
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
}
