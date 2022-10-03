<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Src\Models\Driver\DriverCandidate;
use Src\Models\Driver\DriverInfo;

/**
 * Class DriverCandidateSeeder
 */
class DriverCandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $infos = DriverInfo::with('driver')->get();

        foreach ($infos as $iValue) {
            DriverCandidate::create([
                'tutor_id' => 9,
                'driver_info_id' => $iValue->driver_info_id,
                'franchise_id' => $iValue->franchise_id,
                'learn_status_id' => random_int(1, 4),
                'phone' => random_int(10000000, 9999999999),
                'learn_start' => Carbon::now()->subDays(40),
                'learn_end' => Carbon::now()->subDays(30),
            ]);
        }
    }
}
