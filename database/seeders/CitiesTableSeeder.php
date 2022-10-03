<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class CitiesTableSeeder
 */
class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ini_set('memory_limit', '1024M');
        $path = database_path('files'.DS.'cities.sql');
        DB::unprepared(file_get_contents($path));
        $this->command->info($path);
    }
}
