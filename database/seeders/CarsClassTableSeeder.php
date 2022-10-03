<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class CarsClassTableSeeder
 */
class CarsClassTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('cars_class')->delete();

        DB::table('cars_class')->insert([
            0 =>
                [
                    'car_class_id' => 1,
                    'class_name' => 'Economy',
                    'description' => 'Lorem description',
                    'image' => 'storage/img/car-class-img/car-rate-tabs-id-1.png',
                    'name' => 'words.car_class.economy',
                    'created_at' => now(),
                ],
            1 =>
                [
                    'car_class_id' => 2,
                    'class_name' => 'Comfort',
                    'description' => 'Lorem description',
                    'image' => 'storage/img/car-class-img/car-rate-tabs-id-3.png',
                    'name' => 'words.car_class.comfort',
                    'created_at' => now(),
                ],
            2 =>
                [
                    'car_class_id' => 3,
                    'class_name' => 'Business',
                    'description' => 'Lorem description',
                    'image' => 'storage/img/car-class-img/car-rate-tabs-id-2.png',
                    'name' => 'words.car_class.business',
                    'created_at' => now(),
                ],
            3 =>
                [
                    'car_class_id' => 4,
                    'class_name' => 'Business++',
                    'description' => 'Lorem description',
                    'image' => 'storage/img/car-class-img/car-rate-tabs-id-4.png',
                    'name' => 'words.car_class.business+',
                    'created_at' => now(),
                ],
        ]);
    }
}
