<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class CountriesTableSeeder
 */
class CountriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->delete();

        DB::table('countries')->insert([
            0 =>
                [
                    'country_id' => 1,
                    'name' => 'Afghanistan',
                    'iso_2' => 'AF',
                    'phone_code' => '93',
                    'currency' => 'AFN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            1 =>
                [
                    'country_id' => 2,
                    'name' => 'Aland Islands',
                    'iso_2' => 'AX',
                    'phone_code' => '358-18',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            2 =>
                [
                    'country_id' => 3,
                    'name' => 'Albania',
                    'iso_2' => 'AL',
                    'phone_code' => '355',
                    'currency' => 'ALL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            3 =>
                [
                    'country_id' => 4,
                    'name' => 'Algeria',
                    'iso_2' => 'DZ',
                    'phone_code' => '213',
                    'currency' => 'DZD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            4 =>
                [
                    'country_id' => 5,
                    'name' => 'American Samoa',
                    'iso_2' => 'AS',
                    'phone_code' => '1-684',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            5 =>
                [
                    'country_id' => 6,
                    'name' => 'Andorra',
                    'iso_2' => 'AD',
                    'phone_code' => '376',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            6 =>
                [
                    'country_id' => 7,
                    'name' => 'Angola',
                    'iso_2' => 'AO',
                    'phone_code' => '244',
                    'currency' => 'AOA',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            7 =>
                [
                    'country_id' => 8,
                    'name' => 'Anguilla',
                    'iso_2' => 'AI',
                    'phone_code' => '1-264',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            8 =>
                [
                    'country_id' => 9,
                    'name' => 'Antarctica',
                    'iso_2' => 'AQ',
                    'phone_code' => '',
                    'currency' => '',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            9 =>
                [
                    'country_id' => 10,
                    'name' => 'Antigua And Barbuda',
                    'iso_2' => 'AG',
                    'phone_code' => '1-268',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            10 =>
                [
                    'country_id' => 11,
                    'name' => 'Argentina',
                    'iso_2' => 'AR',
                    'phone_code' => '54',
                    'currency' => 'ARS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            11 =>
                [
                    'country_id' => 12,
                    'name' => 'Armenia',
                    'iso_2' => 'AM',
                    'phone_code' => '374',
                    'currency' => 'AMD',
                    'phone_mask' => '+374(##)-##-##-##',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            12 =>
                [
                    'country_id' => 13,
                    'name' => 'Aruba',
                    'iso_2' => 'AW',
                    'phone_code' => '297',
                    'currency' => 'AWG',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            13 =>
                [
                    'country_id' => 14,
                    'name' => 'Australia',
                    'iso_2' => 'AU',
                    'phone_code' => '61',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            14 =>
                [
                    'country_id' => 15,
                    'name' => 'Austria',
                    'iso_2' => 'AT',
                    'phone_code' => '43',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            15 =>
                [
                    'country_id' => 16,
                    'name' => 'Azerbaijan',
                    'iso_2' => 'AZ',
                    'phone_code' => '994',
                    'currency' => 'AZN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            16 =>
                [
                    'country_id' => 17,
                    'name' => 'Bahamas The',
                    'iso_2' => 'BS',
                    'phone_code' => '1-242',
                    'currency' => 'BSD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            17 =>
                [
                    'country_id' => 18,
                    'name' => 'Bahrain',
                    'iso_2' => 'BH',
                    'phone_code' => '973',
                    'currency' => 'BHD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            18 =>
                [
                    'country_id' => 19,
                    'name' => 'Bangladesh',
                    'iso_2' => 'BD',
                    'phone_code' => '880',
                    'currency' => 'BDT',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            19 =>
                [
                    'country_id' => 20,
                    'name' => 'Barbados',
                    'iso_2' => 'BB',
                    'phone_code' => '1-246',
                    'currency' => 'BBD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            20 =>
                [
                    'country_id' => 21,
                    'name' => 'Belarus',
                    'iso_2' => 'BY',
                    'phone_code' => '375',
                    'currency' => 'BYR',
                    'phone_mask' => '+375(##)###-##-##',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            21 =>
                [
                    'country_id' => 22,
                    'name' => 'Belgium',
                    'iso_2' => 'BE',
                    'phone_code' => '32',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            22 =>
                [
                    'country_id' => 23,
                    'name' => 'Belize',
                    'iso_2' => 'BZ',
                    'phone_code' => '501',
                    'currency' => 'BZD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            23 =>
                [
                    'country_id' => 24,
                    'name' => 'Benin',
                    'iso_2' => 'BJ',
                    'phone_code' => '229',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            24 =>
                [
                    'country_id' => 25,
                    'name' => 'Bermuda',
                    'iso_2' => 'BM',
                    'phone_code' => '1-441',
                    'currency' => 'BMD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            25 =>
                [
                    'country_id' => 26,
                    'name' => 'Bhutan',
                    'iso_2' => 'BT',
                    'phone_code' => '975',
                    'currency' => 'BTN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            26 =>
                [
                    'country_id' => 27,
                    'name' => 'Bolivia',
                    'iso_2' => 'BO',
                    'phone_code' => '591',
                    'currency' => 'BOB',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            27 =>
                [
                    'country_id' => 28,
                    'name' => 'Bosnia and Herzegovina',
                    'iso_2' => 'BA',
                    'phone_code' => null,
                    'currency' => 'BAM',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            28 =>
                [
                    'country_id' => 29,
                    'name' => 'Botswana',
                    'iso_2' => 'BW',
                    'phone_code' => '267',
                    'currency' => 'BWP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            29 =>
                [
                    'country_id' => 30,
                    'name' => 'Bouvet Island',
                    'iso_2' => 'BV',
                    'phone_code' => '',
                    'currency' => 'NOK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            30 =>
                [
                    'country_id' => 31,
                    'name' => 'Brazil',
                    'iso_2' => 'BR',
                    'phone_code' => '55',
                    'currency' => 'BRL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            31 =>
                [
                    'country_id' => 32,
                    'name' => 'British Indian Ocean Territory',
                    'iso_2' => 'IO',
                    'phone_code' => '246',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            32 =>
                [
                    'country_id' => 33,
                    'name' => 'Brunei',
                    'iso_2' => 'BN',
                    'phone_code' => '673',
                    'currency' => 'BND',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            33 =>
                [
                    'country_id' => 34,
                    'name' => 'Bulgaria',
                    'iso_2' => 'BG',
                    'phone_code' => '359',
                    'currency' => 'BGN',
                    'phone_mask' => '+359(###)###-###',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            34 =>
                [
                    'country_id' => 35,
                    'name' => 'Burkina Faso',
                    'iso_2' => 'BF',
                    'phone_code' => '226',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            35 =>
                [
                    'country_id' => 36,
                    'name' => 'Burundi',
                    'iso_2' => 'BI',
                    'phone_code' => '257',
                    'currency' => 'BIF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            36 =>
                [
                    'country_id' => 37,
                    'name' => 'Cambodia',
                    'iso_2' => 'KH',
                    'phone_code' => '855',
                    'currency' => 'KHR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            37 =>
                [
                    'country_id' => 38,
                    'name' => 'Cameroon',
                    'iso_2' => 'CM',
                    'phone_code' => '237',
                    'currency' => 'XAF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            38 =>
                [
                    'country_id' => 39,
                    'name' => 'Canada',
                    'iso_2' => 'CA',
                    'phone_code' => '1',
                    'currency' => 'CAD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            39 =>
                [
                    'country_id' => 40,
                    'name' => 'Cape Verde',
                    'iso_2' => 'CV',
                    'phone_code' => '238',
                    'currency' => 'CVE',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            40 =>
                [
                    'country_id' => 41,
                    'name' => 'Cayman Islands',
                    'iso_2' => 'KY',
                    'phone_code' => '1-345',
                    'currency' => 'KYD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            41 =>
                [
                    'country_id' => 42,
                    'name' => 'Central African Republic',
                    'iso_2' => 'CF',
                    'phone_code' => '236',
                    'currency' => 'XAF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            42 =>
                [
                    'country_id' => 43,
                    'name' => 'Chad',
                    'iso_2' => 'TD',
                    'phone_code' => '235',
                    'currency' => 'XAF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            43 =>
                [
                    'country_id' => 44,
                    'name' => 'Chile',
                    'iso_2' => 'CL',
                    'phone_code' => '56',
                    'currency' => 'CLP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            44 =>
                [
                    'country_id' => 45,
                    'name' => 'China',
                    'iso_2' => 'CN',
                    'phone_code' => '86',
                    'currency' => 'CNY',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            45 =>
                [
                    'country_id' => 46,
                    'name' => 'Christmas Island',
                    'iso_2' => 'CX',
                    'phone_code' => '61',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            46 =>
                [
                    'country_id' => 47,
                    'name' => 'Cocos (Keeling) Islands',
                    'iso_2' => 'CC',
                    'phone_code' => '61',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            47 =>
                [
                    'country_id' => 48,
                    'name' => 'Colombia',
                    'iso_2' => 'CO',
                    'phone_code' => '57',
                    'currency' => 'COP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            48 =>
                [
                    'country_id' => 49,
                    'name' => 'Comoros',
                    'iso_2' => 'KM',
                    'phone_code' => '269',
                    'currency' => 'KMF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            49 =>
                [
                    'country_id' => 50,
                    'name' => 'Congo',
                    'iso_2' => 'CG',
                    'phone_code' => '242',
                    'currency' => 'XAF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            50 =>
                [
                    'country_id' => 51,
                    'name' => 'Congo The Democratic Republic Of The',
                    'iso_2' => 'CD',
                    'phone_code' => '243',
                    'currency' => 'CDF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            51 =>
                [
                    'country_id' => 52,
                    'name' => 'Cook Islands',
                    'iso_2' => 'CK',
                    'phone_code' => '682',
                    'currency' => 'NZD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            52 =>
                [
                    'country_id' => 53,
                    'name' => 'Costa Rica',
                    'iso_2' => 'CR',
                    'phone_code' => '506',
                    'currency' => 'CRC',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            53 =>
                [
                    'country_id' => 54,
                    'name' => 'Cote D&#039;Ivoire (Ivory Coast)',
                    'iso_2' => 'CI',
                    'phone_code' => '225',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            54 =>
                [
                    'country_id' => 55,
                    'name' => 'Croatia (Hrvatska)',
                    'iso_2' => 'HR',
                    'phone_code' => '385',
                    'currency' => 'HRK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            55 =>
                [
                    'country_id' => 56,
                    'name' => 'Cuba',
                    'iso_2' => 'CU',
                    'phone_code' => '53',
                    'currency' => 'CUP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            56 =>
                [
                    'country_id' => 57,
                    'name' => 'Cyprus',
                    'iso_2' => 'CY',
                    'phone_code' => '357',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            57 =>
                [
                    'country_id' => 58,
                    'name' => 'Czech Republic',
                    'iso_2' => 'CZ',
                    'phone_code' => '420',
                    'currency' => 'CZK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            58 =>
                [
                    'country_id' => 59,
                    'name' => 'Denmark',
                    'iso_2' => 'DK',
                    'phone_code' => '45',
                    'currency' => 'DKK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            59 =>
                [
                    'country_id' => 60,
                    'name' => 'Djibouti',
                    'iso_2' => 'DJ',
                    'phone_code' => '253',
                    'currency' => 'DJF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            60 =>
                [
                    'country_id' => 61,
                    'name' => 'Dominica',
                    'iso_2' => 'DM',
                    'phone_code' => '1-767',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            61 =>
                [
                    'country_id' => 62,
                    'name' => 'Dominican Republic',
                    'iso_2' => 'DO',
                    'phone_code' => '1-809 and 1-829',
                    'currency' => 'DOP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            62 =>
                [
                    'country_id' => 63,
                    'name' => 'East Timor',
                    'iso_2' => 'TL',
                    'phone_code' => '670',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            63 =>
                [
                    'country_id' => 64,
                    'name' => 'Ecuador',
                    'iso_2' => 'EC',
                    'phone_code' => '593',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            64 =>
                [
                    'country_id' => 65,
                    'name' => 'Egypt',
                    'iso_2' => 'EG',
                    'phone_code' => '20',
                    'currency' => 'EGP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            65 =>
                [
                    'country_id' => 66,
                    'name' => 'El Salvador',
                    'iso_2' => 'SV',
                    'phone_code' => '503',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            66 =>
                [
                    'country_id' => 67,
                    'name' => 'Equatorial Guinea',
                    'iso_2' => 'GQ',
                    'phone_code' => '240',
                    'currency' => 'XAF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            67 =>
                [
                    'country_id' => 68,
                    'name' => 'Eritrea',
                    'iso_2' => 'ER',
                    'phone_code' => '291',
                    'currency' => 'ERN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            68 =>
                [
                    'country_id' => 69,
                    'name' => 'Estonia',
                    'iso_2' => 'EE',
                    'phone_code' => '372',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            69 =>
                [
                    'country_id' => 70,
                    'name' => 'Ethiopia',
                    'iso_2' => 'ET',
                    'phone_code' => '251',
                    'currency' => 'ETB',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            70 =>
                [
                    'country_id' => 71,
                    'name' => 'Falkland Islands',
                    'iso_2' => 'FK',
                    'phone_code' => '500',
                    'currency' => 'FKP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            71 =>
                [
                    'country_id' => 72,
                    'name' => 'Faroe Islands',
                    'iso_2' => 'FO',
                    'phone_code' => '298',
                    'currency' => 'DKK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            72 =>
                [
                    'country_id' => 73,
                    'name' => 'Fiji Islands',
                    'iso_2' => 'FJ',
                    'phone_code' => '679',
                    'currency' => 'FJD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            73 =>
                [
                    'country_id' => 74,
                    'name' => 'Finland',
                    'iso_2' => 'FI',
                    'phone_code' => '358',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            74 =>
                [
                    'country_id' => 75,
                    'name' => 'France',
                    'iso_2' => 'FR',
                    'phone_code' => '33',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            75 =>
                [
                    'country_id' => 76,
                    'name' => 'French Guiana',
                    'iso_2' => 'GF',
                    'phone_code' => '594',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            76 =>
                [
                    'country_id' => 77,
                    'name' => 'French Polynesia',
                    'iso_2' => 'PF',
                    'phone_code' => '689',
                    'currency' => 'XPF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            77 =>
                [
                    'country_id' => 78,
                    'name' => 'French Southern Territories',
                    'iso_2' => 'TF',
                    'phone_code' => '',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            78 =>
                [
                    'country_id' => 79,
                    'name' => 'Gabon',
                    'iso_2' => 'GA',
                    'phone_code' => '241',
                    'currency' => 'XAF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            79 =>
                [
                    'country_id' => 80,
                    'name' => 'Gambia The',
                    'iso_2' => 'GM',
                    'phone_code' => '220',
                    'currency' => 'GMD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            80 =>
                [
                    'country_id' => 81,
                    'name' => 'Georgia',
                    'iso_2' => 'GE',
                    'phone_code' => '995',
                    'currency' => 'GEL',
                    'phone_mask' => '+995(###)###-###',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            81 =>
                [
                    'country_id' => 82,
                    'name' => 'Germany',
                    'iso_2' => 'DE',
                    'phone_code' => '49',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            82 =>
                [
                    'country_id' => 83,
                    'name' => 'Ghana',
                    'iso_2' => 'GH',
                    'phone_code' => '233',
                    'currency' => 'GHS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            83 =>
                [
                    'country_id' => 84,
                    'name' => 'Gibraltar',
                    'iso_2' => 'GI',
                    'phone_code' => '350',
                    'currency' => 'GIP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            84 =>
                [
                    'country_id' => 85,
                    'name' => 'Greece',
                    'iso_2' => 'GR',
                    'phone_code' => '30',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            85 =>
                [
                    'country_id' => 86,
                    'name' => 'Greenland',
                    'iso_2' => 'GL',
                    'phone_code' => '299',
                    'currency' => 'DKK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            86 =>
                [
                    'country_id' => 87,
                    'name' => 'Grenada',
                    'iso_2' => 'GD',
                    'phone_code' => '1-473',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            87 =>
                [
                    'country_id' => 88,
                    'name' => 'Guadeloupe',
                    'iso_2' => 'GP',
                    'phone_code' => '590',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            88 =>
                [
                    'country_id' => 89,
                    'name' => 'Guam',
                    'iso_2' => 'GU',
                    'phone_code' => '1-671',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            89 =>
                [
                    'country_id' => 90,
                    'name' => 'Guatemala',
                    'iso_2' => 'GT',
                    'phone_code' => '502',
                    'currency' => 'GTQ',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            90 =>
                [
                    'country_id' => 91,
                    'name' => 'Guernsey and Alderney',
                    'iso_2' => 'GG',
                    'phone_code' => '44-1481',
                    'currency' => 'GBP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            91 =>
                [
                    'country_id' => 92,
                    'name' => 'Guinea',
                    'iso_2' => 'GN',
                    'phone_code' => '224',
                    'currency' => 'GNF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            92 =>
                [
                    'country_id' => 93,
                    'name' => 'Guinea-Bissau',
                    'iso_2' => 'GW',
                    'phone_code' => '245',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            93 =>
                [
                    'country_id' => 94,
                    'name' => 'Guyana',
                    'iso_2' => 'GY',
                    'phone_code' => '592',
                    'currency' => 'GYD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            94 =>
                [
                    'country_id' => 95,
                    'name' => 'Haiti',
                    'iso_2' => 'HT',
                    'phone_code' => '509',
                    'currency' => 'HTG',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            95 =>
                [
                    'country_id' => 96,
                    'name' => 'Heard and McDonald Islands',
                    'iso_2' => 'HM',
                    'phone_code' => ' ',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            96 =>
                [
                    'country_id' => 97,
                    'name' => 'Honduras',
                    'iso_2' => 'HN',
                    'phone_code' => '504',
                    'currency' => 'HNL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            97 =>
                [
                    'country_id' => 98,
                    'name' => 'Hong Kong S.A.R.',
                    'iso_2' => 'HK',
                    'phone_code' => '852',
                    'currency' => 'HKD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            98 =>
                [
                    'country_id' => 99,
                    'name' => 'Hungary',
                    'iso_2' => 'HU',
                    'phone_code' => '36',
                    'currency' => 'HUF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            99 =>
                [
                    'country_id' => 100,
                    'name' => 'Iceland',
                    'iso_2' => 'IS',
                    'phone_code' => '354',
                    'currency' => 'ISK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            100 =>
                [
                    'country_id' => 101,
                    'name' => 'India',
                    'iso_2' => 'IN',
                    'phone_code' => '91',
                    'currency' => 'INR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            101 =>
                [
                    'country_id' => 102,
                    'name' => 'Indonesia',
                    'iso_2' => 'ID',
                    'phone_code' => '62',
                    'currency' => 'IDR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            102 =>
                [
                    'country_id' => 103,
                    'name' => 'Iran',
                    'iso_2' => 'IR',
                    'phone_code' => '98',
                    'currency' => 'IRR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            103 =>
                [
                    'country_id' => 104,
                    'name' => 'Iraq',
                    'iso_2' => 'IQ',
                    'phone_code' => '964',
                    'currency' => 'IQD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            104 =>
                [
                    'country_id' => 105,
                    'name' => 'Ireland',
                    'iso_2' => 'IE',
                    'phone_code' => '353',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            105 =>
                [
                    'country_id' => 106,
                    'name' => 'Israel',
                    'iso_2' => 'IL',
                    'phone_code' => '972',
                    'currency' => 'ILS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            106 =>
                [
                    'country_id' => 107,
                    'name' => 'Italy',
                    'iso_2' => 'IT',
                    'phone_code' => '39',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            107 =>
                [
                    'country_id' => 108,
                    'name' => 'Jamaica',
                    'iso_2' => 'JM',
                    'phone_code' => '1-876',
                    'currency' => 'JMD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            108 =>
                [
                    'country_id' => 109,
                    'name' => 'Japan',
                    'iso_2' => 'JP',
                    'phone_code' => '81',
                    'currency' => 'JPY',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            109 =>
                [
                    'country_id' => 110,
                    'name' => 'Jersey',
                    'iso_2' => 'JE',
                    'phone_code' => '44-1534',
                    'currency' => 'GBP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            110 =>
                [
                    'country_id' => 111,
                    'name' => 'Jordan',
                    'iso_2' => 'JO',
                    'phone_code' => '962',
                    'currency' => 'JOD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            111 =>
                [
                    'country_id' => 112,
                    'name' => 'Kazakhstan',
                    'iso_2' => 'KZ',
                    'phone_code' => '7',
                    'currency' => 'KZT',
                    'phone_mask' => '+7(###)###-##-##',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            112 =>
                [
                    'country_id' => 113,
                    'name' => 'Kenya',
                    'iso_2' => 'KE',
                    'phone_code' => '254',
                    'currency' => 'KES',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            113 =>
                [
                    'country_id' => 114,
                    'name' => 'Kiribati',
                    'iso_2' => 'KI',
                    'phone_code' => '686',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            114 =>
                [
                    'country_id' => 115,
                    'name' => 'Korea North
',
                    'iso_2' => 'KP',
                    'phone_code' => '850',
                    'currency' => 'KPW',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            115 =>
                [
                    'country_id' => 116,
                    'name' => 'Korea South',
                    'iso_2' => 'KR',
                    'phone_code' => '82',
                    'currency' => 'KRW',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            116 =>
                [
                    'country_id' => 117,
                    'name' => 'Kuwait',
                    'iso_2' => 'KW',
                    'phone_code' => '965',
                    'currency' => 'KWD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            117 =>
                [
                    'country_id' => 118,
                    'name' => 'Kyrgyzstan',
                    'iso_2' => 'KG',
                    'phone_code' => '996',
                    'currency' => 'KGS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            118 =>
                [
                    'country_id' => 119,
                    'name' => 'Laos',
                    'iso_2' => 'LA',
                    'phone_code' => '856',
                    'currency' => 'LAK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            119 =>
                [
                    'country_id' => 120,
                    'name' => 'Latvia',
                    'iso_2' => 'LV',
                    'phone_code' => '371',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            120 =>
                [
                    'country_id' => 121,
                    'name' => 'Lebanon',
                    'iso_2' => 'LB',
                    'phone_code' => '961',
                    'currency' => 'LBP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            121 =>
                [
                    'country_id' => 122,
                    'name' => 'Lesotho',
                    'iso_2' => 'LS',
                    'phone_code' => '266',
                    'currency' => 'LSL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            122 =>
                [
                    'country_id' => 123,
                    'name' => 'Liberia',
                    'iso_2' => 'LR',
                    'phone_code' => '231',
                    'currency' => 'LRD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            123 =>
                [
                    'country_id' => 124,
                    'name' => 'Libya',
                    'iso_2' => 'LY',
                    'phone_code' => '218',
                    'currency' => 'LYD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            124 =>
                [
                    'country_id' => 125,
                    'name' => 'Liechtenstein',
                    'iso_2' => 'LI',
                    'phone_code' => '423',
                    'currency' => 'CHF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            125 =>
                [
                    'country_id' => 126,
                    'name' => 'Lithuania',
                    'iso_2' => 'LT',
                    'phone_code' => '370',
                    'currency' => 'LTL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            126 =>
                [
                    'country_id' => 127,
                    'name' => 'Luxembourg',
                    'iso_2' => 'LU',
                    'phone_code' => '352',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            127 =>
                [
                    'country_id' => 128,
                    'name' => 'Macau S.A.R.',
                    'iso_2' => 'MO',
                    'phone_code' => '853',
                    'currency' => 'MOP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            128 =>
                [
                    'country_id' => 129,
                    'name' => 'Macedonia',
                    'iso_2' => 'MK',
                    'phone_code' => '389',
                    'currency' => 'MKD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            129 =>
                [
                    'country_id' => 130,
                    'name' => 'Madagascar',
                    'iso_2' => 'MG',
                    'phone_code' => '261',
                    'currency' => 'MGA',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            130 =>
                [
                    'country_id' => 131,
                    'name' => 'Malawi',
                    'iso_2' => 'MW',
                    'phone_code' => '265',
                    'currency' => 'MWK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            131 =>
                [
                    'country_id' => 132,
                    'name' => 'Malaysia',
                    'iso_2' => 'MY',
                    'phone_code' => '60',
                    'currency' => 'MYR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            132 =>
                [
                    'country_id' => 133,
                    'name' => 'Maldives',
                    'iso_2' => 'MV',
                    'phone_code' => '960',
                    'currency' => 'MVR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            133 =>
                [
                    'country_id' => 134,
                    'name' => 'Mali',
                    'iso_2' => 'ML',
                    'phone_code' => '223',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            134 =>
                [
                    'country_id' => 135,
                    'name' => 'Malta',
                    'iso_2' => 'MT',
                    'phone_code' => '356',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            135 =>
                [
                    'country_id' => 136,
                    'name' => 'Man (Isle of)',
                    'iso_2' => 'IM',
                    'phone_code' => '44-1624',
                    'currency' => 'GBP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            136 =>
                [
                    'country_id' => 137,
                    'name' => 'Marshall Islands',
                    'iso_2' => 'MH',
                    'phone_code' => '692',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            137 =>
                [
                    'country_id' => 138,
                    'name' => 'Martinique',
                    'iso_2' => 'MQ',
                    'phone_code' => '596',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            138 =>
                [
                    'country_id' => 139,
                    'name' => 'Mauritania',
                    'iso_2' => 'MR',
                    'phone_code' => '222',
                    'currency' => 'MRO',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            139 =>
                [
                    'country_id' => 140,
                    'name' => 'Mauritius',
                    'iso_2' => 'MU',
                    'phone_code' => '230',
                    'currency' => 'MUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            140 =>
                [
                    'country_id' => 141,
                    'name' => 'Mayotte',
                    'iso_2' => 'YT',
                    'phone_code' => '262',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            141 =>
                [
                    'country_id' => 142,
                    'name' => 'Mexico',
                    'iso_2' => 'MX',
                    'phone_code' => '52',
                    'currency' => 'MXN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            142 =>
                [
                    'country_id' => 143,
                    'name' => 'Micronesia',
                    'iso_2' => 'FM',
                    'phone_code' => '691',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            143 =>
                [
                    'country_id' => 144,
                    'name' => 'Moldova',
                    'iso_2' => 'MD',
                    'phone_code' => '373',
                    'currency' => 'MDL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            144 =>
                [
                    'country_id' => 145,
                    'name' => 'Monaco',
                    'iso_2' => 'MC',
                    'phone_code' => '377',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            145 =>
                [
                    'country_id' => 146,
                    'name' => 'Mongolia',
                    'iso_2' => 'MN',
                    'phone_code' => '976',
                    'currency' => 'MNT',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            146 =>
                [
                    'country_id' => 147,
                    'name' => 'Montenegro',
                    'iso_2' => 'ME',
                    'phone_code' => '382',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            147 =>
                [
                    'country_id' => 148,
                    'name' => 'Montserrat',
                    'iso_2' => 'MS',
                    'phone_code' => '1-664',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            148 =>
                [
                    'country_id' => 149,
                    'name' => 'Morocco',
                    'iso_2' => 'MA',
                    'phone_code' => '212',
                    'currency' => 'MAD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            149 =>
                [
                    'country_id' => 150,
                    'name' => 'Mozambique',
                    'iso_2' => 'MZ',
                    'phone_code' => '258',
                    'currency' => 'MZN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            150 =>
                [
                    'country_id' => 151,
                    'name' => 'Myanmar',
                    'iso_2' => 'MM',
                    'phone_code' => '95',
                    'currency' => 'MMK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            151 =>
                [
                    'country_id' => 152,
                    'name' => 'Namibia',
                    'iso_2' => 'NA',
                    'phone_code' => '264',
                    'currency' => 'NAD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            152 =>
                [
                    'country_id' => 153,
                    'name' => 'Nauru',
                    'iso_2' => 'NR',
                    'phone_code' => '674',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            153 =>
                [
                    'country_id' => 154,
                    'name' => 'Nepal',
                    'iso_2' => 'NP',
                    'phone_code' => '977',
                    'currency' => 'NPR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            154 =>
                [
                    'country_id' => 155,
                    'name' => 'Netherlands Antilles',
                    'iso_2' => 'AN',
                    'phone_code' => '',
                    'currency' => '',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            155 =>
                [
                    'country_id' => 156,
                    'name' => 'Netherlands The',
                    'iso_2' => 'NL',
                    'phone_code' => '31',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            156 =>
                [
                    'country_id' => 157,
                    'name' => 'New Caledonia',
                    'iso_2' => 'NC',
                    'phone_code' => '687',
                    'currency' => 'XPF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            157 =>
                [
                    'country_id' => 158,
                    'name' => 'New Zealand',
                    'iso_2' => 'NZ',
                    'phone_code' => '64',
                    'currency' => 'NZD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            158 =>
                [
                    'country_id' => 159,
                    'name' => 'Nicaragua',
                    'iso_2' => 'NI',
                    'phone_code' => '505',
                    'currency' => 'NIO',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            159 =>
                [
                    'country_id' => 160,
                    'name' => 'Niger',
                    'iso_2' => 'NE',
                    'phone_code' => '227',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            160 =>
                [
                    'country_id' => 161,
                    'name' => 'Nigeria',
                    'iso_2' => 'NG',
                    'phone_code' => '234',
                    'currency' => 'NGN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            161 =>
                [
                    'country_id' => 162,
                    'name' => 'Niue',
                    'iso_2' => 'NU',
                    'phone_code' => '683',
                    'currency' => 'NZD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            162 =>
                [
                    'country_id' => 163,
                    'name' => 'Norfolk Island',
                    'iso_2' => 'NF',
                    'phone_code' => '672',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            163 =>
                [
                    'country_id' => 164,
                    'name' => 'Northern Mariana Islands',
                    'iso_2' => 'MP',
                    'phone_code' => '1-670',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            164 =>
                [
                    'country_id' => 165,
                    'name' => 'Norway',
                    'iso_2' => 'NO',
                    'phone_code' => '47',
                    'currency' => 'NOK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            165 =>
                [
                    'country_id' => 166,
                    'name' => 'Oman',
                    'iso_2' => 'OM',
                    'phone_code' => '968',
                    'currency' => 'OMR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            166 =>
                [
                    'country_id' => 167,
                    'name' => 'Pakistan',
                    'iso_2' => 'PK',
                    'phone_code' => '92',
                    'currency' => 'PKR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            167 =>
                [
                    'country_id' => 168,
                    'name' => 'Palau',
                    'iso_2' => 'PW',
                    'phone_code' => '680',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            168 =>
                [
                    'country_id' => 169,
                    'name' => 'Palestinian Territory Occupied',
                    'iso_2' => 'PS',
                    'phone_code' => '970',
                    'currency' => 'ILS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            169 =>
                [
                    'country_id' => 170,
                    'name' => 'Panama',
                    'iso_2' => 'PA',
                    'phone_code' => '507',
                    'currency' => 'PAB',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            170 =>
                [
                    'country_id' => 171,
                    'name' => 'Papua new Guinea',
                    'iso_2' => 'PG',
                    'phone_code' => '675',
                    'currency' => 'PGK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            171 =>
                [
                    'country_id' => 172,
                    'name' => 'Paraguay',
                    'iso_2' => 'PY',
                    'phone_code' => '595',
                    'currency' => 'PYG',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            172 =>
                [
                    'country_id' => 173,
                    'name' => 'Peru',
                    'iso_2' => 'PE',
                    'phone_code' => '51',
                    'currency' => 'PEN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            173 =>
                [
                    'country_id' => 174,
                    'name' => 'Philippines',
                    'iso_2' => 'PH',
                    'phone_code' => '63',
                    'currency' => 'PHP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            174 =>
                [
                    'country_id' => 175,
                    'name' => 'Pitcairn Island',
                    'iso_2' => 'PN',
                    'phone_code' => '870',
                    'currency' => 'NZD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            175 =>
                [
                    'country_id' => 176,
                    'name' => 'Poland',
                    'iso_2' => 'PL',
                    'phone_code' => '48',
                    'currency' => 'PLN',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            176 =>
                [
                    'country_id' => 177,
                    'name' => 'Portugal',
                    'iso_2' => 'PT',
                    'phone_code' => '351',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            177 =>
                [
                    'country_id' => 178,
                    'name' => 'Puerto Rico',
                    'iso_2' => 'PR',
                    'phone_code' => '1-787',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            178 =>
                [
                    'country_id' => 179,
                    'name' => 'Qatar',
                    'iso_2' => 'QA',
                    'phone_code' => '974',
                    'currency' => 'QAR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            179 =>
                [
                    'country_id' => 180,
                    'name' => 'Reunion',
                    'iso_2' => 'RE',
                    'phone_code' => '262',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            180 =>
                [
                    'country_id' => 181,
                    'name' => 'Romania',
                    'iso_2' => 'RO',
                    'phone_code' => '40',
                    'currency' => 'RON',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            181 =>
                [
                    'country_id' => 182,
                    'name' => 'Russia',
                    'iso_2' => 'RU',
                    'phone_code' => '7',
                    'currency' => 'RUB',
                    'phone_mask' => '+7(###)-###-##-##',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            182 =>
                [
                    'country_id' => 183,
                    'name' => 'Rwanda',
                    'iso_2' => 'RW',
                    'phone_code' => '250',
                    'currency' => 'RWF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            183 =>
                [
                    'country_id' => 184,
                    'name' => 'Saint Helena',
                    'iso_2' => 'SH',
                    'phone_code' => '290',
                    'currency' => 'SHP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            184 =>
                [
                    'country_id' => 185,
                    'name' => 'Saint Kitts And Nevis',
                    'iso_2' => 'KN',
                    'phone_code' => '1-869',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            185 =>
                [
                    'country_id' => 186,
                    'name' => 'Saint Lucia',
                    'iso_2' => 'LC',
                    'phone_code' => '1-758',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            186 =>
                [
                    'country_id' => 187,
                    'name' => 'Saint Pierre and Miquelon',
                    'iso_2' => 'PM',
                    'phone_code' => '508',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            187 =>
                [
                    'country_id' => 188,
                    'name' => 'Saint Vincent And The Grenadines',
                    'iso_2' => 'VC',
                    'phone_code' => '1-784',
                    'currency' => 'XCD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            188 =>
                [
                    'country_id' => 189,
                    'name' => 'Saint-Barthelemy',
                    'iso_2' => 'BL',
                    'phone_code' => '590',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            189 =>
                [
                    'country_id' => 190,
                    'name' => 'Saint-Martin (French part)',
                    'iso_2' => 'MF',
                    'phone_code' => '590',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            190 =>
                [
                    'country_id' => 191,
                    'name' => 'Samoa',
                    'iso_2' => 'WS',
                    'phone_code' => '685',
                    'currency' => 'WST',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            191 =>
                [
                    'country_id' => 192,
                    'name' => 'San Marino',
                    'iso_2' => 'SM',
                    'phone_code' => '378',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            192 =>
                [
                    'country_id' => 193,
                    'name' => 'Sao Tome and Principe',
                    'iso_2' => 'ST',
                    'phone_code' => '239',
                    'currency' => 'STD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            193 =>
                [
                    'country_id' => 194,
                    'name' => 'Saudi Arabia',
                    'iso_2' => 'SA',
                    'phone_code' => '966',
                    'currency' => 'SAR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            194 =>
                [
                    'country_id' => 195,
                    'name' => 'Senegal',
                    'iso_2' => 'SN',
                    'phone_code' => '221',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            195 =>
                [
                    'country_id' => 196,
                    'name' => 'Serbia',
                    'iso_2' => 'RS',
                    'phone_code' => '381',
                    'currency' => 'RSD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            196 =>
                [
                    'country_id' => 197,
                    'name' => 'Seychelles',
                    'iso_2' => 'SC',
                    'phone_code' => '248',
                    'currency' => 'SCR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            197 =>
                [
                    'country_id' => 198,
                    'name' => 'Sierra Leone',
                    'iso_2' => 'SL',
                    'phone_code' => '232',
                    'currency' => 'SLL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            198 =>
                [
                    'country_id' => 199,
                    'name' => 'Singapore',
                    'iso_2' => 'SG',
                    'phone_code' => '65',
                    'currency' => 'SGD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            199 =>
                [
                    'country_id' => 200,
                    'name' => 'Slovakia',
                    'iso_2' => 'SK',
                    'phone_code' => '421',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            200 =>
                [
                    'country_id' => 201,
                    'name' => 'Slovenia',
                    'iso_2' => 'SI',
                    'phone_code' => '386',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            201 =>
                [
                    'country_id' => 202,
                    'name' => 'Solomon Islands',
                    'iso_2' => 'SB',
                    'phone_code' => '677',
                    'currency' => 'SBD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            202 =>
                [
                    'country_id' => 203,
                    'name' => 'Somalia',
                    'iso_2' => 'SO',
                    'phone_code' => '252',
                    'currency' => 'SOS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            203 =>
                [
                    'country_id' => 204,
                    'name' => 'South Africa',
                    'iso_2' => 'ZA',
                    'phone_code' => '27',
                    'currency' => 'ZAR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            204 =>
                [
                    'country_id' => 205,
                    'name' => 'South Georgia',
                    'iso_2' => 'GS',
                    'phone_code' => '',
                    'currency' => 'GBP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            205 =>
                [
                    'country_id' => 206,
                    'name' => 'South Sudan',
                    'iso_2' => 'SS',
                    'phone_code' => '211',
                    'currency' => 'SSP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            206 =>
                [
                    'country_id' => 207,
                    'name' => 'Spain',
                    'iso_2' => 'ES',
                    'phone_code' => '34',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            207 =>
                [
                    'country_id' => 208,
                    'name' => 'Sri Lanka',
                    'iso_2' => 'LK',
                    'phone_code' => '94',
                    'currency' => 'LKR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            208 =>
                [
                    'country_id' => 209,
                    'name' => 'Sudan',
                    'iso_2' => 'SD',
                    'phone_code' => '249',
                    'currency' => 'SDG',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            209 =>
                [
                    'country_id' => 210,
                    'name' => 'Suriname',
                    'iso_2' => 'SR',
                    'phone_code' => '597',
                    'currency' => 'SRD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            210 =>
                [
                    'country_id' => 211,
                    'name' => 'Svalbard And Jan Mayen Islands',
                    'iso_2' => 'SJ',
                    'phone_code' => '47',
                    'currency' => 'NOK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            211 =>
                [
                    'country_id' => 212,
                    'name' => 'Swaziland',
                    'iso_2' => 'SZ',
                    'phone_code' => '268',
                    'currency' => 'SZL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            212 =>
                [
                    'country_id' => 213,
                    'name' => 'Sweden',
                    'iso_2' => 'SE',
                    'phone_code' => '46',
                    'currency' => 'SEK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            213 =>
                [
                    'country_id' => 214,
                    'name' => 'Switzerland',
                    'iso_2' => 'CH',
                    'phone_code' => '41',
                    'currency' => 'CHF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            214 =>
                [
                    'country_id' => 215,
                    'name' => 'Syria',
                    'iso_2' => 'SY',
                    'phone_code' => '963',
                    'currency' => 'SYP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            215 =>
                [
                    'country_id' => 216,
                    'name' => 'Taiwan',
                    'iso_2' => 'TW',
                    'phone_code' => '886',
                    'currency' => 'TWD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            216 =>
                [
                    'country_id' => 217,
                    'name' => 'Tajikistan',
                    'iso_2' => 'TJ',
                    'phone_code' => '992',
                    'currency' => 'TJS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            217 =>
                [
                    'country_id' => 218,
                    'name' => 'Tanzania',
                    'iso_2' => 'TZ',
                    'phone_code' => '255',
                    'currency' => 'TZS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            218 =>
                [
                    'country_id' => 219,
                    'name' => 'Thailand',
                    'iso_2' => 'TH',
                    'phone_code' => '66',
                    'currency' => 'THB',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            219 =>
                [
                    'country_id' => 220,
                    'name' => 'Togo',
                    'iso_2' => 'TG',
                    'phone_code' => '228',
                    'currency' => 'XOF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            220 =>
                [
                    'country_id' => 221,
                    'name' => 'Tokelau',
                    'iso_2' => 'TK',
                    'phone_code' => '690',
                    'currency' => 'NZD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            221 =>
                [
                    'country_id' => 222,
                    'name' => 'Tonga',
                    'iso_2' => 'TO',
                    'phone_code' => '676',
                    'currency' => 'TOP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            222 =>
                [
                    'country_id' => 223,
                    'name' => 'Trinidad And Tobago',
                    'iso_2' => 'TT',
                    'phone_code' => '1-868',
                    'currency' => 'TTD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            223 =>
                [
                    'country_id' => 224,
                    'name' => 'Tunisia',
                    'iso_2' => 'TN',
                    'phone_code' => '216',
                    'currency' => 'TND',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            224 =>
                [
                    'country_id' => 225,
                    'name' => 'Turkey',
                    'iso_2' => 'TR',
                    'phone_code' => '90',
                    'currency' => 'TRY',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            225 =>
                [
                    'country_id' => 226,
                    'name' => 'Turkmenistan',
                    'iso_2' => 'TM',
                    'phone_code' => '993',
                    'currency' => 'TMT',
                    'phone_mask' => '+993-#-###-####',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            226 =>
                [
                    'country_id' => 227,
                    'name' => 'Turks And Caicos Islands',
                    'iso_2' => 'TC',
                    'phone_code' => '1-649',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            227 =>
                [
                    'country_id' => 228,
                    'name' => 'Tuvalu',
                    'iso_2' => 'TV',
                    'phone_code' => '688',
                    'currency' => 'AUD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            228 =>
                [
                    'country_id' => 229,
                    'name' => 'Uganda',
                    'iso_2' => 'UG',
                    'phone_code' => '256',
                    'currency' => 'UGX',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            229 =>
                [
                    'country_id' => 230,
                    'name' => 'Ukraine',
                    'iso_2' => 'UA',
                    'phone_code' => '380',
                    'currency' => 'UAH',
                    'phone_mask' => '+380(##)###-##-##',

                    'created_at' => null,
                    'updated_at' => null,
                ],
            230 =>
                [
                    'country_id' => 231,
                    'name' => 'United Arab Emirates',
                    'iso_2' => 'AE',
                    'phone_code' => '971',
                    'currency' => 'AED',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            231 =>
                [
                    'country_id' => 232,
                    'name' => 'United Kingdom',
                    'iso_2' => 'GB',
                    'phone_code' => '44',
                    'currency' => 'GBP',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            232 =>
                [
                    'country_id' => 233,
                    'name' => 'United States',
                    'iso_2' => 'US',
                    'phone_code' => '1',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            233 =>
                [
                    'country_id' => 234,
                    'name' => 'United States Minor Outlying Islands',
                    'iso_2' => 'UM',
                    'phone_code' => '1',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            234 =>
                [
                    'country_id' => 235,
                    'name' => 'Uruguay',
                    'iso_2' => 'UY',
                    'phone_code' => '598',
                    'currency' => 'UYU',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            235 =>
                [
                    'country_id' => 236,
                    'name' => 'Uzbekistan',
                    'iso_2' => 'UZ',
                    'phone_code' => '998',
                    'currency' => 'UZS',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            236 =>
                [
                    'country_id' => 237,
                    'name' => 'Vanuatu',
                    'iso_2' => 'VU',
                    'phone_code' => '678',
                    'currency' => 'VUV',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            237 =>
                [
                    'country_id' => 238,
                    'name' => 'Vatican City State (Holy See)',
                    'iso_2' => 'VA',
                    'phone_code' => '379',
                    'currency' => 'EUR',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            238 =>
                [
                    'country_id' => 239,
                    'name' => 'Venezuela',
                    'iso_2' => 'VE',
                    'phone_code' => '58',
                    'currency' => 'VEF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            239 =>
                [
                    'country_id' => 240,
                    'name' => 'Vietnam',
                    'iso_2' => 'VN',
                    'phone_code' => '84',
                    'currency' => 'VND',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            240 =>
                [
                    'country_id' => 241,
                    'name' => 'Virgin Islands (British)',
                    'iso_2' => 'VG',
                    'phone_code' => '1-284',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            241 =>
                [
                    'country_id' => 242,
                    'name' => 'Virgin Islands (US)',
                    'iso_2' => 'VI',
                    'phone_code' => '1-340',
                    'currency' => 'USD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            242 =>
                [
                    'country_id' => 243,
                    'name' => 'Wallis And Futuna Islands',
                    'iso_2' => 'WF',
                    'phone_code' => '681',
                    'currency' => 'XPF',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            243 =>
                [
                    'country_id' => 244,
                    'name' => 'Western Sahara',
                    'iso_2' => 'EH',
                    'phone_code' => '212',
                    'currency' => 'MAD',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            244 =>
                [
                    'country_id' => 245,
                    'name' => 'Yemen',
                    'iso_2' => 'YE',
                    'phone_code' => '967',
                    'currency' => 'YER',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            245 =>
                [
                    'country_id' => 246,
                    'name' => 'Zambia',
                    'iso_2' => 'ZM',
                    'phone_code' => '260',
                    'currency' => 'ZMK',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
            246 =>
                [
                    'country_id' => 247,
                    'name' => 'Zimbabwe',
                    'iso_2' => 'ZW',
                    'phone_code' => '263',
                    'currency' => 'ZWL',
                    'phone_mask' => null,

                    'created_at' => null,
                    'updated_at' => null,
                ],
        ]);
    }
}
