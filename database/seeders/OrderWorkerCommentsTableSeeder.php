<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderWorkerComment;

/**
 * Class OrderWorkerCommentsTableSeeder
 * @package Database\Seeders
 */
class OrderWorkerCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Faker  $faker
     * @return void
     */
    public function run(Faker $faker)
    {
        $orders = Order::whereHas('operator')->with('operator')->get();
        foreach ($orders as $order) {
            for ($i = 0; $i < 10; $i++) {
                OrderWorkerComment::create([
                    'order_id' => $order->order_id,
                    'system_worker_id' => $order->operator->system_worker_id,
                    'text' => $faker->text
                ]);
            }
        }
    }
}
