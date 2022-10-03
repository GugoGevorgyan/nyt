<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CompanyTariffTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_tariff')->delete();

        DB::table('company_tariff')->insert(array(
            0 =>
                array(
                    'company_id' => 1,
                    'company_tariff_id' => 1,
                    'created_at' => '2021-09-24 18:27:23',
                    'tariff_id' => 25,
                ),
            1 =>
                array(
                    'company_id' => 2,
                    'company_tariff_id' => 2,
                    'created_at' => '2021-09-24 18:27:23',
                    'tariff_id' => 26,
                ),
            2 =>
                array(
                    'company_id' => 3,
                    'company_tariff_id' => 3,
                    'created_at' => '2021-09-24 18:27:23',
                    'tariff_id' => 27,
                ),
            3 =>
                array(
                    'company_id' => 3,
                    'company_tariff_id' => 4,
                    'created_at' => '2021-09-24 18:27:23',
                    'tariff_id' => 28,
                ),
            4 =>
                array(
                    'company_id' => 4,
                    'company_tariff_id' => 5,
                    'created_at' => '2021-09-24 20:34:54',
                    'tariff_id' => 27,
                ),
            5 =>
                array(
                    'company_id' => 4,
                    'company_tariff_id' => 6,
                    'created_at' => '2021-09-24 20:34:54',
                    'tariff_id' => 28,
                ),
            6 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 7,
                    'created_at' => '2021-09-27 16:31:49',
                    'tariff_id' => 31,
                ),
            7 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 8,
                    'created_at' => '2021-09-27 16:31:49',
                    'tariff_id' => 32,
                ),
            8 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 9,
                    'created_at' => '2021-09-27 16:31:49',
                    'tariff_id' => 33,
                ),
            9 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 10,
                    'created_at' => '2021-09-27 16:31:49',
                    'tariff_id' => 34,
                ),
            10 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 11,
                    'created_at' => '2021-09-27 16:45:43',
                    'tariff_id' => 35,
                ),
            11 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 12,
                    'created_at' => '2021-09-27 16:45:44',
                    'tariff_id' => 36,
                ),
            12 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 13,
                    'created_at' => '2021-09-27 17:10:47',
                    'tariff_id' => 37,
                ),
            13 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 14,
                    'created_at' => '2021-09-27 17:10:47',
                    'tariff_id' => 38,
                ),
            14 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 15,
                    'created_at' => '2021-09-27 17:58:07',
                    'tariff_id' => 39,
                ),
            15 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 16,
                    'created_at' => '2021-09-27 17:58:08',
                    'tariff_id' => 40,
                ),
            16 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 17,
                    'created_at' => '2021-09-27 17:58:08',
                    'tariff_id' => 41,
                ),
            17 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 18,
                    'created_at' => '2021-09-27 17:58:08',
                    'tariff_id' => 42,
                ),
            18 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 19,
                    'created_at' => '2021-09-27 17:58:09',
                    'tariff_id' => 43,
                ),
            19 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 20,
                    'created_at' => '2021-09-27 17:58:09',
                    'tariff_id' => 44,
                ),
            20 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 21,
                    'created_at' => '2021-09-27 17:58:09',
                    'tariff_id' => 45,
                ),
            21 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 22,
                    'created_at' => '2021-09-27 17:58:09',
                    'tariff_id' => 46,
                ),
            22 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 23,
                    'created_at' => '2021-09-27 17:58:10',
                    'tariff_id' => 47,
                ),
            23 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 24,
                    'created_at' => '2021-09-27 17:58:10',
                    'tariff_id' => 48,
                ),
            24 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 25,
                    'created_at' => '2021-09-27 17:58:10',
                    'tariff_id' => 49,
                ),
            25 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 26,
                    'created_at' => '2021-09-27 17:58:10',
                    'tariff_id' => 50,
                ),
            26 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 27,
                    'created_at' => '2021-09-27 17:58:11',
                    'tariff_id' => 51,
                ),
            27 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 28,
                    'created_at' => '2021-09-27 17:58:11',
                    'tariff_id' => 52,
                ),
            28 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 29,
                    'created_at' => '2021-09-27 18:21:25',
                    'tariff_id' => 53,
                ),
            29 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 30,
                    'created_at' => '2021-09-27 18:21:25',
                    'tariff_id' => 54,
                ),
            30 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 31,
                    'created_at' => '2021-09-27 18:21:25',
                    'tariff_id' => 55,
                ),
            31 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 32,
                    'created_at' => '2021-09-27 18:21:26',
                    'tariff_id' => 56,
                ),
            32 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 33,
                    'created_at' => '2021-09-27 18:21:26',
                    'tariff_id' => 57,
                ),
            33 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 34,
                    'created_at' => '2021-09-27 18:21:26',
                    'tariff_id' => 58,
                ),
            34 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 35,
                    'created_at' => '2021-09-27 18:21:26',
                    'tariff_id' => 59,
                ),
            35 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 36,
                    'created_at' => '2021-09-27 18:21:27',
                    'tariff_id' => 60,
                ),
            36 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 37,
                    'created_at' => '2021-09-27 18:21:27',
                    'tariff_id' => 61,
                ),
            37 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 38,
                    'created_at' => '2021-09-27 18:21:27',
                    'tariff_id' => 62,
                ),
            38 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 39,
                    'created_at' => '2021-09-27 18:21:27',
                    'tariff_id' => 63,
                ),
            39 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 40,
                    'created_at' => '2021-09-27 18:21:28',
                    'tariff_id' => 64,
                ),
            40 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 41,
                    'created_at' => '2021-09-27 18:21:28',
                    'tariff_id' => 65,
                ),
            41 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 42,
                    'created_at' => '2021-09-27 18:21:28',
                    'tariff_id' => 66,
                ),
            42 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 43,
                    'created_at' => '2021-09-27 18:21:29',
                    'tariff_id' => 67,
                ),
            43 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 44,
                    'created_at' => '2021-09-27 18:21:29',
                    'tariff_id' => 68,
                ),
            44 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 45,
                    'created_at' => '2021-09-27 18:21:29',
                    'tariff_id' => 69,
                ),
            45 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 46,
                    'created_at' => '2021-09-27 18:21:29',
                    'tariff_id' => 70,
                ),
            46 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 47,
                    'created_at' => '2021-09-27 18:21:30',
                    'tariff_id' => 71,
                ),
            47 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 48,
                    'created_at' => '2021-09-27 18:21:30',
                    'tariff_id' => 72,
                ),
            48 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 49,
                    'created_at' => '2021-09-27 18:21:30',
                    'tariff_id' => 73,
                ),
            49 =>
                array(
                    'company_id' => 5,
                    'company_tariff_id' => 50,
                    'created_at' => '2021-09-27 18:21:30',
                    'tariff_id' => 74,
                ),
        ));
    }
}
