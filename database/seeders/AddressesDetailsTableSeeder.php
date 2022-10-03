<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AddressesDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses_details')->delete();

        DB::table('addresses_details')->insert(array(
            0 =>
                array(
                    'address_detail_id' => 37,
                    'created_at' => '2020-06-17 12:47:30',
                    'distance' => 3688,
                    'duration' => 2655,
                    'end_address_id' => 26,
                    'initial_address_id' => 21,
                ),
            1 =>
                array(
                    'address_detail_id' => 38,
                    'created_at' => '2020-06-17 12:47:43',
                    'distance' => 3557,
                    'duration' => 2561,
                    'end_address_id' => 26,
                    'initial_address_id' => 24,
                ),
            2 =>
                array(
                    'address_detail_id' => 39,
                    'created_at' => '2020-06-17 12:47:47',
                    'distance' => 3058,
                    'duration' => 2202,
                    'end_address_id' => 27,
                    'initial_address_id' => 24,
                ),
            3 =>
                array(
                    'address_detail_id' => 40,
                    'created_at' => '2020-06-17 12:57:45',
                    'distance' => 3719,
                    'duration' => 2678,
                    'end_address_id' => 27,
                    'initial_address_id' => 30,
                ),
            4 =>
                array(
                    'address_detail_id' => 41,
                    'created_at' => '2020-06-17 13:42:59',
                    'distance' => 1099,
                    'duration' => 791,
                    'end_address_id' => 20,
                    'initial_address_id' => 18,
                ),
            5 =>
                array(
                    'address_detail_id' => 44,
                    'created_at' => '2020-06-17 17:37:27',
                    'distance' => 1245,
                    'duration' => 896,
                    'end_address_id' => 27,
                    'initial_address_id' => 38,
                ),
            6 =>
                array(
                    'address_detail_id' => 45,
                    'created_at' => '2020-06-17 17:37:34',
                    'distance' => 3344,
                    'duration' => 2408,
                    'end_address_id' => 31,
                    'initial_address_id' => 38,
                ),
            7 =>
                array(
                    'address_detail_id' => 46,
                    'created_at' => '2020-06-17 17:38:46',
                    'distance' => 2705,
                    'duration' => 1947,
                    'end_address_id' => 31,
                    'initial_address_id' => 21,
                ),
            8 =>
                array(
                    'address_detail_id' => 47,
                    'created_at' => '2020-06-17 18:11:25',
                    'distance' => 3344,
                    'duration' => 2408,
                    'end_address_id' => 38,
                    'initial_address_id' => 39,
                ),
            9 =>
                array(
                    'address_detail_id' => 48,
                    'created_at' => '2020-06-17 18:20:13',
                    'distance' => 3688,
                    'duration' => 2655,
                    'end_address_id' => 21,
                    'initial_address_id' => 38,
                ),
            10 =>
                array(
                    'address_detail_id' => 49,
                    'created_at' => '2020-06-17 18:21:37',
                    'distance' => 664,
                    'duration' => 478,
                    'end_address_id' => 29,
                    'initial_address_id' => 21,
                ),
            11 =>
                array(
                    'address_detail_id' => 50,
                    'created_at' => '2020-06-17 18:24:26',
                    'distance' => 1364,
                    'duration' => 982,
                    'end_address_id' => 30,
                    'initial_address_id' => 21,
                ),
            12 =>
                array(
                    'address_detail_id' => 51,
                    'created_at' => '2020-06-17 18:29:53',
                    'distance' => 1600,
                    'duration' => 1152,
                    'end_address_id' => 18,
                    'initial_address_id' => 21,
                ),
            13 =>
                array(
                    'address_detail_id' => 52,
                    'created_at' => '2020-06-17 18:30:08',
                    'distance' => 1600,
                    'duration' => 1152,
                    'end_address_id' => 21,
                    'initial_address_id' => 18,
                ),
            14 =>
                array(
                    'address_detail_id' => 53,
                    'created_at' => '2020-06-17 18:33:23',
                    'distance' => 2618,
                    'duration' => 1885,
                    'end_address_id' => 39,
                    'initial_address_id' => 18,
                ),
            15 =>
                array(
                    'address_detail_id' => 55,
                    'created_at' => '2020-06-17 18:33:49',
                    'distance' => 2705,
                    'duration' => 1947,
                    'end_address_id' => 39,
                    'initial_address_id' => 21,
                ),
            16 =>
                array(
                    'address_detail_id' => 56,
                    'created_at' => '2020-06-17 18:34:00',
                    'distance' => 3688,
                    'duration' => 2655,
                    'end_address_id' => 38,
                    'initial_address_id' => 21,
                ),
            17 =>
                array(
                    'address_detail_id' => 57,
                    'created_at' => '2020-06-17 18:39:22',
                    'distance' => 474,
                    'duration' => 341,
                    'end_address_id' => 18,
                    'initial_address_id' => 30,
                ),
            18 =>
                array(
                    'address_detail_id' => 58,
                    'created_at' => '2020-06-17 18:47:25',
                    'distance' => 10956,
                    'duration' => 7888,
                    'end_address_id' => 41,
                    'initial_address_id' => 18,
                ),
            19 =>
                array(
                    'address_detail_id' => 59,
                    'created_at' => '2020-06-17 18:47:42',
                    'distance' => 474,
                    'duration' => 341,
                    'end_address_id' => 30,
                    'initial_address_id' => 18,
                ),
            20 =>
                array(
                    'address_detail_id' => 60,
                    'created_at' => '2020-06-18 10:46:24',
                    'distance' => 3190,
                    'duration' => 2296,
                    'end_address_id' => 27,
                    'initial_address_id' => 21,
                ),
            21 =>
                array(
                    'address_detail_id' => 61,
                    'created_at' => '2020-06-18 11:42:35',
                    'distance' => 1583,
                    'duration' => 1140,
                    'end_address_id' => 21,
                    'initial_address_id' => 44,
                ),
            22 =>
                array(
                    'address_detail_id' => 62,
                    'created_at' => '2020-06-18 12:00:07',
                    'distance' => 1598,
                    'duration' => 1151,
                    'end_address_id' => 21,
                    'initial_address_id' => 45,
                ),
            23 =>
                array(
                    'address_detail_id' => 63,
                    'created_at' => '2020-06-18 12:44:40',
                    'distance' => 1630,
                    'duration' => 1173,
                    'end_address_id' => 21,
                    'initial_address_id' => 46,
                ),
            24 =>
                array(
                    'address_detail_id' => 64,
                    'created_at' => '2020-06-18 13:21:58',
                    'distance' => 1293,
                    'duration' => 931,
                    'end_address_id' => 21,
                    'initial_address_id' => 47,
                ),
            25 =>
                array(
                    'address_detail_id' => 65,
                    'created_at' => '2020-06-18 13:22:39',
                    'distance' => 1154,
                    'duration' => 831,
                    'end_address_id' => 21,
                    'initial_address_id' => 49,
                ),
            26 =>
                array(
                    'address_detail_id' => 66,
                    'created_at' => '2020-06-18 13:22:57',
                    'distance' => 1076,
                    'duration' => 774,
                    'end_address_id' => 21,
                    'initial_address_id' => 50,
                ),
            27 =>
                array(
                    'address_detail_id' => 67,
                    'created_at' => '2020-06-18 13:24:01',
                    'distance' => 903,
                    'duration' => 650,
                    'end_address_id' => 21,
                    'initial_address_id' => 51,
                ),
            28 =>
                array(
                    'address_detail_id' => 68,
                    'created_at' => '2020-06-18 13:36:41',
                    'distance' => 344,
                    'duration' => 248,
                    'end_address_id' => 49,
                    'initial_address_id' => 47,
                ),
            29 =>
                array(
                    'address_detail_id' => 69,
                    'created_at' => '2020-06-18 13:36:59',
                    'distance' => 518,
                    'duration' => 373,
                    'end_address_id' => 50,
                    'initial_address_id' => 47,
                ),
            30 =>
                array(
                    'address_detail_id' => 70,
                    'created_at' => '2020-06-18 13:37:24',
                    'distance' => 4198,
                    'duration' => 3022,
                    'end_address_id' => 47,
                    'initial_address_id' => 38,
                ),
            31 =>
                array(
                    'address_detail_id' => 71,
                    'created_at' => '2020-06-18 13:37:51',
                    'distance' => 4474,
                    'duration' => 3221,
                    'end_address_id' => 44,
                    'initial_address_id' => 38,
                ),
            32 =>
                array(
                    'address_detail_id' => 72,
                    'created_at' => '2020-06-18 13:38:53',
                    'distance' => 1844,
                    'duration' => 1328,
                    'end_address_id' => 44,
                    'initial_address_id' => 22,
                ),
            33 =>
                array(
                    'address_detail_id' => 73,
                    'created_at' => '2020-06-18 13:41:00',
                    'distance' => 10524,
                    'duration' => 7577,
                    'end_address_id' => 41,
                    'initial_address_id' => 49,
                ),
            34 =>
                array(
                    'address_detail_id' => 74,
                    'created_at' => '2020-06-18 14:40:49',
                    'distance' => 1870,
                    'duration' => 1346,
                    'end_address_id' => 41,
                    'initial_address_id' => 43,
                ),
            35 =>
                array(
                    'address_detail_id' => 75,
                    'created_at' => '2020-06-18 14:40:55',
                    'distance' => 4921,
                    'duration' => 3543,
                    'end_address_id' => 38,
                    'initial_address_id' => 43,
                ),
            36 =>
                array(
                    'address_detail_id' => 76,
                    'created_at' => '2020-06-18 14:41:08',
                    'distance' => 3303,
                    'duration' => 2378,
                    'end_address_id' => 38,
                    'initial_address_id' => 29,
                ),
            37 =>
                array(
                    'address_detail_id' => 77,
                    'created_at' => '2020-06-18 14:41:17',
                    'distance' => 664,
                    'duration' => 478,
                    'end_address_id' => 21,
                    'initial_address_id' => 29,
                ),
            38 =>
                array(
                    'address_detail_id' => 78,
                    'created_at' => '2020-06-18 14:42:05',
                    'distance' => 2705,
                    'duration' => 1947,
                    'end_address_id' => 21,
                    'initial_address_id' => 39,
                ),
            39 =>
                array(
                    'address_detail_id' => 79,
                    'created_at' => '2020-06-18 14:42:17',
                    'distance' => 877,
                    'duration' => 631,
                    'end_address_id' => 21,
                    'initial_address_id' => 28,
                ),
            40 =>
                array(
                    'address_detail_id' => 80,
                    'created_at' => '2020-06-18 14:43:48',
                    'distance' => 1373,
                    'duration' => 988,
                    'end_address_id' => 21,
                    'initial_address_id' => 20,
                ),
            41 =>
                array(
                    'address_detail_id' => 81,
                    'created_at' => '2020-06-18 15:42:53',
                    'distance' => 1249,
                    'duration' => 899,
                    'end_address_id' => 23,
                    'initial_address_id' => 18,
                ),
            42 =>
                array(
                    'address_detail_id' => 82,
                    'created_at' => '2020-06-18 15:53:20',
                    'distance' => 3992,
                    'duration' => 2874,
                    'end_address_id' => 27,
                    'initial_address_id' => 18,
                ),
            43 =>
                array(
                    'address_detail_id' => 83,
                    'created_at' => '2020-06-18 16:04:41',
                    'distance' => 1188,
                    'duration' => 855,
                    'end_address_id' => 29,
                    'initial_address_id' => 18,
                ),
            44 =>
                array(
                    'address_detail_id' => 84,
                    'created_at' => '2020-06-18 16:46:52',
                    'distance' => 329,
                    'duration' => 237,
                    'end_address_id' => 47,
                    'initial_address_id' => 18,
                ),
            45 =>
                array(
                    'address_detail_id' => 85,
                    'created_at' => '2020-06-19 11:42:22',
                    'distance' => 3990,
                    'duration' => 2873,
                    'end_address_id' => 27,
                    'initial_address_id' => 45,
                ),
            46 =>
                array(
                    'address_detail_id' => 86,
                    'created_at' => '2020-06-19 14:03:17',
                    'distance' => 1122,
                    'duration' => 808,
                    'end_address_id' => 85,
                    'initial_address_id' => 18,
                ),
        ));
    }
}
