<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class CarOptionsTableSeeder
 */
class CarOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('car_options')->delete();

        DB::table('car_options')->insert([
            0 =>
                [
                    'car_option_id' => 1,
                    'option' => 'Детское кресло',
                    'price' => '500.00',
                    'name' => 'messages.car_option_text_baby',
                    'value' => 'baby_seat',
                    'created_at' => now(),
                ],
            1 =>
                [
                    'car_option_id' => 2,
                    'option' => 'Встреча с табличкой',
                    'price' => '250.00',
                    'name' => 'messages.car_option_text_tablet',
                    'value' => 'meet',
                    'created_at' => now(),
                ],
            2 =>
                [
                    'car_option_id' => 3,
                    'option' => 'Перевозка животных (более 5 кг)',
                    'price' => '750.00',
                    'name' => 'messages.car_option_text_animal',
                    'value' => 'animal',
                    'created_at' => now(),
                ],
            3 =>
                [
                    'car_option_id' => 4,
                    'option' => 'Большой багажник',
                    'price' => '100.00',
                    'name' => 'messages.car_option_text_baggage',
                    'value' => 'big_baggage',
                    'created_at' => now(),
                ],
        ]);
    }
}
