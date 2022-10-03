<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\SystemUsers\WorkerOperator;

/**
 * Class WorkerOperatorsTableSeeder
 */
class WorkerOperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $workers = SystemWorker::whereHas('roles', fn($q) => $q->where('roles.name', '=', 'operator_web'))->get();

        foreach ($workers as $i => $iValue) {
            WorkerOperator::create([
                'system_worker_id' => $iValue->system_worker_id,
                'franchise_sub_phone_id' => $i + 1,
            ]);
        }
    }
}
