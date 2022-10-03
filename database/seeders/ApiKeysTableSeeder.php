<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ApiKeysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_keys')->delete();

        DB::table('api_keys')->insert(array(
            0 =>
                array(
                    'api_key_id' => 1,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'Y_GEOCODE',
                    'params' => '[{"key": "5eea94c9-f45d-4b11-8514-4d1c6eed9081", "used": true, "version": "1.x"}, {"key": "45b3aaf3-6d70-459d-980e-30269585db64", "used": false, "version": "1.x"}, {"key": "35fe8878-c521-43c3-b49f-414b2275e106", "used": false, "version": "1.x"}, {"key": "5d1fc909-59a6-43a1-b38f-d712285a68ba", "used": false, "version": "1.x"}, {"key": "48b04669-951d-4972-85c7-32f4fd0d9510", "used": false, "version": "1.x"}]',
                    'type' => 1,
                    'updated_at' => '2021-04-02 13:12:35',
                    'url' => 'https://geocode-maps.yandex.ru/$[version]/?apikey=$[key]&',
                ),
            1 =>
                array(
                    'api_key_id' => 2,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'Y_MATRIX',
                    'params' => '[{"key": "244dc63e-5740-4962-a884-91a0d2e2b06a", "used": true, "version": "v2"}]',
                    'type' => 2,
                    'updated_at' => null,
                    'url' => 'https://api.routing.yandex.net/$[version]/distancematrix?apikey=$[key]&',
                ),
            2 =>
                array(
                    'api_key_id' => 3,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'Y_ROUTE',
                    'params' => '[{"key": "244dc63e-5740-4962-a884-91a0d2e2b06a", "used": true, "version": "v2"}]',
                    'type' => 3,
                    'updated_at' => null,
                    'url' => 'https://api.routing.yandex.net/$[version]/route?apikey=$[key]&',
                ),
            3 =>
                array(
                    'api_key_id' => 4,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'Y_MAP',
                    'params' => '[{"key": "5eea94c9-f45d-4b11-8514-4d1c6eed9081", "used": true, "version": "2.1.79"}, {"key": "45b3aaf3-6d70-459d-980e-30269585db64", "used": false, "version": "2.1.79"}, {"key": "35fe8878-c521-43c3-b49f-414b2275e106", "used": false, "version": "2.1.79"}, {"key": "5d1fc909-59a6-43a1-b38f-d712285a68ba", "used": false, "version": "2.1.79"}, {"key": "48b04669-951d-4972-85c7-32f4fd0d9510", "used": false, "version": "2.1.79"}]',
                    'type' => 4,
                    'updated_at' => null,
                    'url' => 'https://api-maps.yandex.ru/$[version]/?apikey=$[key]&',
                ),
            4 =>
                array(
                    'api_key_id' => 6,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'GIBDD',
                    'params' => '[{"key": "79d2c234edf08cec8360cf1ddc31baf6", "used": true, "version": "v2"}]',
                    'type' => 5,
                    'updated_at' => null,
                    'url' => 'http://highload.kocherov.net/parser/api/avtokod_fines_api/?key=$[GBDD_KEY]&sts=',
                ),
            5 =>
                array(
                    'api_key_id' => 7,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'AIR_FLIGHT',
                    'params' => '[{"key": "7d80ec0768f3d8172455eccaac48eb28", "used": true, "version": "v1"}]',
                    'type' => 6,
                    'updated_at' => null,
                    'url' => 'https://api.aviationstack.com/$[version]/flights/$[key]',
                ),
            6 =>
                array(
                    'api_key_id' => 8,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'ACQUIRING_API_TEST',
                    'params' => '{"key": "1526fec01b5d11f4df4f2160627ce351", "used": true, "version": "v1", "merchant_id": "c24360cfac0a0c40c518405f6bc68cb0"}',
                    'type' => 7,
                    'updated_at' => null,
                    'url' => 'https://paydev.invoice.su/api/$[version]/',
                ),
            7 =>
                array(
                    'api_key_id' => 9,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'ACQUIRING_API_PROD',
                    'params' => '{"key": "1526fec01b5d11f4df4f2160627ce351", "used": true, "version": "v1", "merchant_id": "c24360cfac0a0c40c518405f6bc68cb0"}',
                    'type' => 8,
                    'updated_at' => null,
                    'url' => 'https://secure.invoice.su/api/$[version]/',
                ),
            8 =>
                array(
                    'api_key_id' => 10,
                    'created_at' => null,
                    'iterator' => null,
                    'name' => 'Y_BUSINESS',
                    'params' => '{"key": "AQAAAABUcfXqAAVM1dDDb4WOhUZGrTalSL70C1s", "used": true, "version": "1.8"}',
                    'type' => 9,
                    'updated_at' => null,
                    'url' => 'https://business.taxi.yandex.ru/api/$[version]/',
                ),
        ));
    }
}
