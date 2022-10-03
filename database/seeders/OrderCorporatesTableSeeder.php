<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Order\Order;

class OrderCorporatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::with(['client.corporateCompanies'])->whereIn('order_type_id', [2, 3])->get();
        foreach ($orders as $order) {
            $order->corporate()->create([
                'company_id' => $order->client->corporateCompanies[rand(0, count($order->client->corporateCompanies) - 1)]->company_id
            ]);
        }
    }
}
