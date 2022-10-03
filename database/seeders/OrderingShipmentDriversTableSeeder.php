<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Order\OrderShippedDriver;
use Src\Models\RatingSystem\EstimatedRating;
use Str;

/**
 * Class OrderingShipmentDriversTableSeeder
 */
class OrderingShipmentDriversTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $estimated = EstimatedRating::get();

        foreach ($estimated as $i => $iValue) {
            OrderShippedDriver::create(
                [
                    'driver_id' => $iValue->driver_id,
                    'order_id' => $iValue->order_id,
                    'estimated_rating_id' => $iValue->estimated_rating_id,
                    'status_id' => 2,
                    'current' => 1,
                    'accept_hash' => Str::random('16'),
                ]
            );
        }
    }
}
