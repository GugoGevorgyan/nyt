<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class RailwayStationsTableSeeder
 */
class RailwayStationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('railway_stations')->delete();

        DB::table('railway_stations')->insert(
            [
                0 =>
                    [
                        'railway_station_id' => 1,
                        'city_id' => 99972,
                        'name' => 'Белорусский',
                        'lat' => 55.776618,
                        'lut' => 37.581496,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                1 =>
                    [
                        'railway_station_id' => 2,
                        'city_id' => 99972,
                        'name' => 'Казанский',
                        'lat' => 55.773444,
                        'lut' => 37.655329,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                2 =>
                    [
                        'railway_station_id' => 3,
                        'city_id' => 99972,
                        'name' => 'Киевский',
                        'lat' => 55.742975,
                        'lut' => 37.566359,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                3 =>
                    [
                        'railway_station_id' => 4,
                        'city_id' => 99972,
                        'name' => 'Курский',
                        'lat' => 55.756989,
                        'lut' => 37.66123,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                4 =>
                    [
                        'railway_station_id' => 5,
                        'city_id' => 99972,
                        'name' => 'Ленинградский',
                        'lat' => 55.776451,
                        'lut' => 37.655212,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                5 =>
                    [
                        'railway_station_id' => 6,
                        'city_id' => 99972,
                        'name' => 'Павелецкий',
                        'lat' => 55.729823,
                        'lut' => 37.640596,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                6 =>
                    [
                        'railway_station_id' => 7,
                        'city_id' => 99972,
                        'name' => 'Рижский',
                        'lat' => 55.792746,
                        'lut' => 37.632565,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                7 =>
                    [
                        'railway_station_id' => 8,
                        'city_id' => 99972,
                        'name' => 'Савёловский',
                        'lat' => 55.794355,
                        'lut' => 37.58826,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
                8 =>
                    [
                        'railway_station_id' => 9,
                        'city_id' => 99972,
                        'name' => 'Ярославский',
                        'lat' => 55.776684,
                        'lut' => 37.657332,
                        'created_at' => '2020-07-27 14:09:44.000000',
                    ],
            ]
        );
    }
}
