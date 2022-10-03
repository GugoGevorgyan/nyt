<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Src\Models\Client\Client;

/**
 * Class ClientSettingsTableSeeder
 */
class ClientSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = Client::all();

        DB::table('client_settings')->delete();

        foreach ($clients as $client) {
            $client->setting()->create(['show_driver_my_coordinates' => 0, 'not_call' => 0]);
        }
    }
}
