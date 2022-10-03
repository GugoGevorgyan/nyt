<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 *
 */
class VersioningTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        \DB::table('versioning')->delete();

        \DB::table('versioning')->insert([
            0 =>
                [
                    'app' => 1,
                    'auth_key' => 'UVnzS7nhH4RcIQa7vN56oodufKWVd3TF',
                    'device' => 2,
                    'state' => 3,
                    'updated_at' => Carbon::now(),
                    'version' => '1.0.0',
                    'version_id' => 1,
                ],
            1 =>
                [
                    'app' => 2,
                    'auth_key' => 'Gy7rt1OlpKM780puncw8wb6hi3lHGnNa',
                    'device' => 2,
                    'state' => 3,
                    'updated_at' => Carbon::now(),
                    'version' => '1.0.0',
                    'version_id' => 2,
                ],
            2 =>
                [
                    'app' => 2,
                    'auth_key' => 'ojxxkDW2Qmu4VuTqpF8CKDRF56lsCphW',
                    'device' => 1,
                    'state' => 3,
                    'updated_at' => Carbon::now(),
                    'version' => '1.0.0',
                    'version_id' => 3,
                ],
            3 =>
                [
                    'app' => 3,
                    'auth_key' => 'ADgMdhHpQxv3XngtstQNC87tMJUO6wgv',
                    'device' => 3,
                    'state' => 2,
                    'updated_at' => Carbon::now(),
                    'version' => '1.0.0',
                    'version_id' => 4,
                ],
            4 =>
                [
                    'app' => 4,
                    'auth_key' => 'HBSJ5NMBt3YOgHpsG5c1WPYy7KUMQHni',
                    'device' => 2,
                    'state' => 2,
                    'updated_at' => Carbon::now(),
                    'version' => '1.0.0',
                    'version_id' => 5,
                ],
        ]);
    }
}
