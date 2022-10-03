<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use JsonException;


/**
 * Class TimezonesTableSeeder
 */
class TimezonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws JsonException
     */
    public function run(): void
    {
        ini_set('memory_limit', '1024M');

        DB::table('timezones')->delete();

        $path = json_decode(file_get_contents(database_path('files'.DS.'timezones.json')), true, 512, JSON_THROW_ON_ERROR);

        foreach ($path as $name => $gmt) {
            DB::table('timezones')->insert(['zone_string' => $name, 'zone_gmt' => $gmt]);
        }
    }
}
