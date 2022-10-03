<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AirportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('airports')->delete();
        
        \DB::table('airports')->insert(array (
            0 => 
            array (
                'address' => 'Россия, Москва, посёлок Внуково, 2-я Рейсовая улица, 2к5',
                'airport_id' => 1,
                'city_id' => 99972,
                'created_at' => '2020-07-27 14:09:44',
                'lat' => '55.60559200',
                'lut' => '37.28719900',
                'name' => 'Внуково',
                'terminal' => 'AAA',
            ),
            1 => 
            array (
                'address' => 'Россия, Московская область, городской округ Домодедово, аэропорт Домодедово имени М.В. Ломоносова, 1',
                'airport_id' => 2,
                'city_id' => 99972,
                'created_at' => '2020-07-27 14:09:44',
                'lat' => '55.41434800',
                'lut' => '37.90048800',
                'name' => 'Домодедово',
                'terminal' => 'AAA',
            ),
            2 => 
            array (
                'address' => 'Россия, Московская область, Химки, Шереметьевское шоссе, вл37',
                'airport_id' => 3,
                'city_id' => 99972,
                'created_at' => '2020-07-27 14:09:44',
                'lat' => '55.98135400',
                'lut' => '37.41448100',
                'name' => 'Шереметьево',
                'terminal' => 'AAA',
            ),
            3 => 
            array (
                'address' => 'Россия, Московская область, Жуковский, улица Наркомвод, 3',
                'airport_id' => 4,
                'city_id' => 99972,
                'created_at' => '2020-07-27 14:09:44',
                'lat' => '55.56167000',
                'lut' => '38.11773600',
                'name' => 'Жуковский',
                'terminal' => 'AAA',
            ),
            4 => 
            array (
                'address' => 'Россия, Москва, поселение Рязановское, квартал № 1, 1с3',
                'airport_id' => 5,
                'city_id' => 99972,
                'created_at' => '2022-01-25 02:56:15',
                'lat' => '55.50292100',
                'lut' => '37.51074500',
                'name' => 'Остафьево',
                'terminal' => 'AAA',
            ),
            5 => 
            array (
                'address' => 'Армения, Ереван, аэропорт Звартноц ',
                'airport_id' => 6,
                'city_id' => 581,
                'created_at' => '2022-01-25 03:00:00',
                'lat' => '40.15257000',
                'lut' => '44.39997900',
                'name' => 'Zvartnotc',
                'terminal' => 'AAA',
            ),
        ));
        
        
    }
}