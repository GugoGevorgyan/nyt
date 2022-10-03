<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Order\Order;
use Src\Models\RatingSystem\EstimatedRating;

/**
 * Class EstimatedRatingsTableSeeder
 */
class EstimatedRatingsTableSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        $orders = Order::whereIn('status_id', [2, 3, 4])
            ->with([
                'franchise.drivers' =>
                    function ($q) {
                        $q->whereHas('car');
                    }
            ])->get();

        foreach ($orders as $i => $iValue) {
            if (count($iValue->franchise) > 0) {
                $drivers = $iValue->franchise[0]->drivers;
                if (count($drivers) > 0) {
                    EstimatedRating::create(
                        [
                            'driver_id' => $drivers[random_int(0, count($drivers) - 1)]->driver_id,
                            'order_id' => $iValue->order_id,
                            'added_rating' => '5',
                            'remove_rating' => '0',
                            'added_patterns' => 1,
                            'remove_patterns' => 2
                        ]
                    );
                }
            }
        }
    }
}
