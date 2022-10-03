<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Client\Client;
use Src\Models\Driver\Driver;
use Src\Models\Order\CanceledOrder;
use Src\Models\Order\CompletedOrder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderFeedback;
use Src\Models\Order\OrderFeedbackOption;

class OrderFeedbacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $orders = Order::whereHas('canceled')->orWhereHas('completed')->with(['canceled', 'completed', 'driver'])->get();
        $completedOptions = OrderFeedbackOption::where('completed', '=', 1)->get();
        $canceledOptions = OrderFeedbackOption::where('completed', '=', 0)->get();

        foreach ($orders as $order) {
            $option = null;
            if (rand(0, 1)) {
                $option = $order->canceled ?
                    $canceledOptions[rand(0, count($canceledOptions) - 1)]->order_feedback_option_id :
                    $completedOptions[rand(0, count($completedOptions) - 1)]->order_feedback_option_id;
            }

            OrderFeedback::create([
                'order_id' => $order->order_id,
                'orderable_id' => $order->canceled ? $order->canceled->canceled_order_id : $order->completed->completed_order_id,
                'orderable_type' => $order->canceled ? (new CanceledOrder())->getMap() : (new CompletedOrder())->getMap(),
                'writable_id' => $order->client_id,
                'writable_type' => (new Client())->getMap(),
                'readable_id' => $order->completed ? $order->driver->driver_id : null,
                'readable_type' => $order->completed ? (new Driver())->getMap() : null,
                'text' => $option ? null : $faker->text,
                'assessment' => $order->canceled ? 0 : rand(1, 5),
                'feedback_option_id' => $option ? $option : null,
            ]);

            if ($order->completed) {
                OrderFeedback::create([
                    'order_id' => $order->order_id,
                    'orderable_id' => $order->completed->completed_order_id,
                    'orderable_type' => (new CompletedOrder())->getMap(),
                    'writable_id' => $order->driver->driver_id,
                    'writable_type' => (new Driver())->getMap(),
                    'readable_id' => $order->client_id,
                    'readable_type' => (new Client())->getMap(),
                    'text' => $faker->text,
                    'assessment' => rand(1, 5),
                    'feedback_option_id' => null,
                ]);
            }
        }
    }
}
