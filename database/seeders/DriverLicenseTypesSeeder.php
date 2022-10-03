<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Src\Models\Driver\Driver;

/**
 * Class DriverLicenseTypesSeeder
 */
class DriverLicenseTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('driver_license_types')->delete();

        DB::table('driver_license_types')->insert([
            0 => [
                'name' => 'TYPE_A',
                'type' => Driver::LICENSE_TYPE_A
            ],
            1 => [
                'name' => 'TYPE_A1',
                'type' => Driver::LICENSE_TYPE_A1
            ],
            2 => [
                'name' => 'TYPE_B',
                'type' => Driver::LICENSE_TYPE_B
            ],
            3 => [
                'name' => 'TYPE_B1',
                'type' => Driver::LICENSE_TYPE_B1
            ],
            4 => [
                'name' => 'TYPE_BE',
                'type' => Driver::LICENSE_TYPE_BE
            ],
            5 => [
                'name' => 'TYPE_C',
                'type' => Driver::LICENSE_TYPE_C
            ],
            6 => [
                'name' => 'TYPE_C1',
                'type' => Driver::LICENSE_TYPE_C1
            ],
            7 => [
                'name' => 'TYPE_CE',
                'type' => Driver::LICENSE_TYPE_CE
            ],
            8 => [
                'name' => 'TYPE_CE1',
                'type' => Driver::LICENSE_TYPE_CE1
            ],
            9 => [
                'name' => 'TYPE_D',
                'type' => Driver::LICENSE_TYPE_D
            ],
            10 => [
                'name' => 'TYPE_D1',
                'type' => Driver::LICENSE_TYPE_D1
            ],
            11 => [
                'name' => 'TYPE_DE',
                'type' => Driver::LICENSE_TYPE_DE
            ],
        ]);
    }
}
