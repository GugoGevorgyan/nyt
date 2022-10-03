<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Src\Core\Enums\ConstUtil;

/**
 * Class RegionTableSeeder
 */
class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = database_path('files'.DS.'regions.sql');
        DB::unprepared(file_get_contents($path));
        $this->command->info($path);
    }
}
