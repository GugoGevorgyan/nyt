<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Src\Core\Traits\OauthTrait;

/**
 * Class OauthClientsTableSeeder
 */
class OauthClientsTableSeeder extends Seeder
{
    use OauthTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('oauth_clients')->delete();

        $this->createClientSecret('CLIENTS');
        $this->createClientSecret('DRIVERS', 'drivers');
        $this->createClientSecret('TERMINAL', 'terminals');
        $this->createClientSecret('DRIVERS_TERMINAL', 'drivers');
        $this->createClientSecret('SYSTEM_WORKERS', 'system_workers');

        DB::table('oauth_clients')->insert(
            [
                6 =>
                    [
                        'name' => 'ATC',
                        'secret' => 'lEWuT1UvJYMXbIBoSPJkjiisWtUHtFfWgGz5rdVasVkkugAATWKCGV8PUhrjzLj5',
                        'redirect' => config('app.name'),
                        'personal_access_client' => 0,
                        'password_client' => 0,
                        'revoked' => 0,
                    ],
            ]
        );
    }
}

