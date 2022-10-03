<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Client\ClientAddress;
use Src\Models\Corporate\CorporateClient;

/**
 * Class ClientAddressTableSeeder
 */
class ClientAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  Faker  $faker
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('client_addresses')->delete();

        $corporateClients = CorporateClient::get();

        foreach ($corporateClients as $xValue) {
            ClientAddress::create(
                [
                    'client_id' => $xValue->client_id,
                    'name' => 'Home',
                    'short_address' => 'Komitas 56',
                    'address' => $faker->address,
                    'favorite' => $faker->boolean,
                    'lat' => "5236548.78",
                    'lut' => "9656215.78",
                ]
            );
        }
    }
}
