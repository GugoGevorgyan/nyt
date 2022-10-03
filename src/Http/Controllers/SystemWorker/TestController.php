<?php

namespace Src\Http\Controllers\SystemWorker;

use Src\Http\Controllers\Controller;
use Src\Models\Car\Car;
use Src\Models\Driver\Driver;
use Src\Models\Order\Order;
use Src\Models\Order\OrderCommon;
use Src\Models\Order\OrderShippedDriver;

class TestController extends Controller
{

    public function index()
    {
        $orders = Order::whereIn('status_id', [2, 3, 4])->with(['franchise.drivers'])->get();
        dd($orders->toArray());
    }

    public function orderAccept($driver_id, $order_id)
    {
        $shippeds = OrderShippedDriver::where('driver_id', '=', $driver_id)
            ->orWhere('order_id', '=', $order_id)
            ->get();

        foreach ($shippeds as $shipped) {
            if ($shipped->current) {
                $shipped->update(['current' => false]);
            }
        }

        $shipped_pending = OrderShippedDriver::create([
            'driver_id' => $driver_id,
            'order_id' => $order_id,
            'current' => true,
            'status_id' => 1,
            'accept_hash' => \Illuminate\Support\Str::random('16')
        ]);

        sleep(10);

        $shipped_pending->update(['current' => false]);

        $shipped_accept = OrderShippedDriver::create([
            'driver_id' => $driver_id,
            'order_id' => $order_id,
            'current' => true,
            'status_id' => 2,
            'accept_hash' => \Illuminate\Support\Str::random('16')
        ]);

        $shipped_accept->driver->update(['current_status_id' => 2]);

        dd($shipped_accept);
    }

    public function orderCommon()
    {
        $orders = Order::where('status_id', '=', 1)->whereDoesntHave('common')->get();
        $drivers = Driver::select('driver_id')
            ->where('current_franchise_id', '=', 1)
            ->where('current_status_id', '=', 1)
            ->where('online', '=', 1)->get();

        $driver_ids = $drivers->pluck('driver_id')->toArray();

        foreach ($orders as $order) {
            OrderCommon::create([
                'order_id' => $order->order_id,
                'driver_ids' => ['ids' => $driver_ids]
            ]);
        }
    }

    public function driverShipmentUpdate($shipment_id)
    {
        $shipment = OrderShippedDriver::find($shipment_id);
        $shipment->update(['current' => 0]);
    }

    public function driverShipment($driver_id, $order_id)
    {
        dd(OrderShippedDriver::create(
            [
                'driver_id' => $driver_id,
                'order_id' => $order_id,
                'estimated_rating_id' => 1,
                'status_id' => 2,
                'current' => 1,
                'accept_hash' => \Illuminate\Support\Str::random('16'),
            ]
        ));
    }

    public function driversCoordsUpdate()
    {
        $drivers = Driver::where('online', '=', 1)->get();
        foreach ($drivers as $driver) {
            $driver->update(['lat' => $driver->lat + 0.1, 'lut' => $driver->lut + 0.1]);
        }

        dd(count($drivers));
    }

    public function driversStatusUpdate()
    {
        $drivers = Driver::where('online', '=', 1)->get();
        foreach ($drivers as $driver) {
            $driver->update(['current_status_id' => rand(1, 5)]);
        }

        dd(count($drivers));
    }

    public function ordersStatusUpdate()
    {
        $orders = Order::whereHas('franchise', function ($q) {
            return $q->where('franchise_id', '=', 1);
        })->get();

        foreach ($orders as $order) {
            $order->update(['status_id' => rand(1, 5)]);
        }
        dd(count($orders));
    }

    public function orderStatusUpdate($order_id)
    {
        $order = Order::find($order_id);
        $order->update(['status_id' => rand(1, 5)]);

        dd('done');
    }

    public function carUpdate()
    {
        $cars = Car::all();
        foreach ($cars as $car) {
            $car->update(['garage_number' => rand(1000, 9999)]);
        }
        dd(count($cars));
    }
}
