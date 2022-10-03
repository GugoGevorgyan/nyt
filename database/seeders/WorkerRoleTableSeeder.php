<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorkerRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        \DB::table('worker_role')->delete();

        \DB::table('worker_role')->insert([
            0 =>
                [
                    'worker_role_id' => 87,
                    'system_worker_id' => 1,
                    'role_id' => 1,
                    'created_at' => '2021-11-25 16:08:12',
                ],
            1 =>
                [
                    'worker_role_id' => 88,
                    'system_worker_id' => 1,
                    'role_id' => 2,
                    'created_at' => '2021-11-25 16:08:12',
                ],
            2 =>
                [
                    'worker_role_id' => 89,
                    'system_worker_id' => 1,
                    'role_id' => 3,
                    'created_at' => '2021-11-25 16:08:13',
                ],
            3 =>
                [
                    'worker_role_id' => 90,
                    'system_worker_id' => 1,
                    'role_id' => 4,
                    'created_at' => '2021-11-25 16:08:13',
                ],
            4 =>
                [
                    'worker_role_id' => 91,
                    'system_worker_id' => 1,
                    'role_id' => 5,
                    'created_at' => '2021-11-25 16:08:13',
                ],
            5 =>
                [
                    'worker_role_id' => 92,
                    'system_worker_id' => 1,
                    'role_id' => 6,
                    'created_at' => '2021-11-25 16:08:13',
                ],
            6 =>
                [
                    'worker_role_id' => 93,
                    'system_worker_id' => 1,
                    'role_id' => 7,
                    'created_at' => '2021-11-25 16:08:14',
                ],
            7 =>
                [
                    'worker_role_id' => 94,
                    'system_worker_id' => 1,
                    'role_id' => 8,
                    'created_at' => '2021-11-25 16:08:14',
                ],
            8 =>
                [
                    'worker_role_id' => 95,
                    'system_worker_id' => 1,
                    'role_id' => 9,
                    'created_at' => '2021-11-25 16:08:14',
                ],
            9 =>
                [
                    'worker_role_id' => 96,
                    'system_worker_id' => 1,
                    'role_id' => 10,
                    'created_at' => '2021-11-25 16:08:14',
                ],
            10 =>
                [
                    'worker_role_id' => 97,
                    'system_worker_id' => 1,
                    'role_id' => 11,
                    'created_at' => '2021-11-25 16:08:14',
                ],
            11 =>
                [
                    'worker_role_id' => 98,
                    'system_worker_id' => 1,
                    'role_id' => 12,
                    'created_at' => '2021-11-25 16:08:15',
                ],
            12 =>
                [
                    'worker_role_id' => 99,
                    'system_worker_id' => 1,
                    'role_id' => 13,
                    'created_at' => '2021-11-25 16:08:15',
                ],
            13 =>
                [
                    'worker_role_id' => 100,
                    'system_worker_id' => 1,
                    'role_id' => 14,
                    'created_at' => '2021-11-25 16:08:15',
                ],
            14 =>
                [
                    'worker_role_id' => 101,
                    'system_worker_id' => 1,
                    'role_id' => 15,
                    'created_at' => '2021-11-25 16:08:15',
                ],
            15 =>
                [
                    'worker_role_id' => 102,
                    'system_worker_id' => 1,
                    'role_id' => 16,
                    'created_at' => '2021-11-25 16:08:16',
                ],
            16 =>
                [
                    'worker_role_id' => 103,
                    'system_worker_id' => 1,
                    'role_id' => 17,
                    'created_at' => '2021-11-25 16:08:16',
                ],
            17 =>
                [
                    'worker_role_id' => 104,
                    'system_worker_id' => 1,
                    'role_id' => 18,
                    'created_at' => '2021-11-25 16:08:16',
                ],
            18 =>
                [
                    'worker_role_id' => 105,
                    'system_worker_id' => 1,
                    'role_id' => 19,
                    'created_at' => '2021-11-25 16:08:16',
                ],
            19 =>
                [
                    'worker_role_id' => 106,
                    'system_worker_id' => 1,
                    'role_id' => 20,
                    'created_at' => '2021-11-25 16:08:16',
                ],
            20 =>
                [
                    'worker_role_id' => 107,
                    'system_worker_id' => 1,
                    'role_id' => 21,
                    'created_at' => '2021-11-25 16:08:17',
                ],
            21 =>
                [
                    'worker_role_id' => 108,
                    'system_worker_id' => 1,
                    'role_id' => 22,
                    'created_at' => '2021-11-25 16:08:17',
                ],
            22 =>
                [
                    'worker_role_id' => 109,
                    'system_worker_id' => 1,
                    'role_id' => 23,
                    'created_at' => '2021-11-25 16:08:17',
                ],
            23 =>
                [
                    'worker_role_id' => 110,
                    'system_worker_id' => 1,
                    'role_id' => 24,
                    'created_at' => '2021-11-25 16:08:17',
                ],
        ]);
    }
}
