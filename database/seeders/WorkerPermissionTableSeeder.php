<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class WorkerPermissionTableSeeder
 */
class WorkerPermissionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('worker_permission')->delete();

        DB::table('worker_permission')->insert([
            0 =>
                [
                    'permission_id' => 164,
                    'system_worker_id' => 1,
                    'worker_permission_id' => 1,
                ],
            1 =>
                [
                    'permission_id' => 163,
                    'system_worker_id' => 64,
                    'worker_permission_id' => 2,
                ],
        ]);
    }
}
