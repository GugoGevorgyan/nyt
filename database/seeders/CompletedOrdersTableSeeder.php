<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Src\Models\Order\CompletedOrder;
use Src\Models\Order\Order;

/**
 * Class CompletedOrdersTableSeeder
 */
class CompletedOrdersTableSeeder extends Seeder
{
    /** // @TODO REMOVE ALL COMPLAINTS IDS
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $orders = Order::where('status_id', '=', 4)->with('driver')->get();

        foreach ($orders as $iValue) {
            if ($iValue->driver) {
                CompletedOrder::create([
                    'order_id' => $iValue->order_id,
                    'driver_id' => $iValue->driver->driver_id,
                    'car_id' => $iValue->driver->car_id,
                    'distance' => 25,
                    'duration' => 25,
                    'cost' => random_int(300, 4000),
                    'trajectory' => json_encode([['lat' => 44.4960455, 'lut' => 40.2056898]], JSON_THROW_ON_ERROR),
                    'created_at' => Carbon::now()->subDays(random_int(0, 30)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
