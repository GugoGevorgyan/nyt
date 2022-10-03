<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Exception;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Franchise\Franchise;
use Src\Models\Park;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class ParksTableSeeder
 */
class ParksTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @param  Faker  $faker
     * @return void
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        DB::table('parks')->delete();

        $franchises = Franchise::get();

        foreach ($franchises as $xValue) {
            $managers = SystemWorker::where('franchise_id', '=', $xValue->franchise_id)
                ->whereHas('roles', fn($q) => $q->where('name', '=', 'park_manager_web'))
                ->get();

            foreach ($managers as $manager) {
                $manager_id = $manager->system_worker_id;

                if (1 === $xValue->franchise_id) {
                    $manager_id = random_int(0, 1) ? 1 : $manager->system_worker_id;
                }

                Park::create([
                    'name' => $faker->firstName.' park',
                    'city_id' => 99972,
                    'manager_id' => $manager_id,
                    'entity_id' => $xValue->entity_id,
                    'address' => $faker->address,
                    'franchise_id' => $xValue->franchise_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
