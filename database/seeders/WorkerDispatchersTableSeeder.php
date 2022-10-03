<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\SystemUsers\WorkerDispatcher;

/**
 * Class WorkerDispatchersTableSeeder
 */
class WorkerDispatchersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $workers = SystemWorker::whereHas('roles', fn($q) => $q->where('roles.name', '=', 'dispatcher_web'))->get();

        foreach ($workers as $i => $i_value) {
            WorkerDispatcher::create(['system_worker_id' => $i_value->system_worker_id, 'franchise_sub_phone_id' => $i + 2]);
        }
    }
}
