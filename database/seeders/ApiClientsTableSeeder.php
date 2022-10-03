<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\SystemUsers\ApiClient;

/**
 * Class ApiClientsTableSeeder
 */
class ApiClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiClient::create([
            'name' => 'NYT_ATC_TOKEN',
            'secret' => 'n98u6jBEXnZP4G9uff3fMJ9GyyBTCB5RLSvIEaP6',
            'type' => ApiClient::CLIENT_TYPE_ATC
        ]);
    }
}
