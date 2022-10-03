<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Hash;
use Illuminate\Database\Seeder;

/**
 * Class SuperAdminTableSeeder
 */
class SuperAdminTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('super_admin')->delete();

        DB::table('super_admin')->insert([
            0 =>
                [
                    'created_at' => null,
                    'email' => 'vasiliyt@mail.ru',
                    'name' => 'Vasiliy',
                    'password' => Hash::make('secret'),
                    'updated_at' => null,
                ],
            1 =>
                [
                    'created_at' => null,
                    'email' => 'vladimir@mail.ru',
                    'name' => 'Vladimir',
                    'password' => Hash::make('secret'),
                    'updated_at' => null,
                ],
            2 =>
                [
                    'created_at' => null,
                    'email' => 'vitaliy@mail.ru',
                    'name' => 'Vitaliy',
                    'password' => Hash::make('secret'),
                    'updated_at' => null,
                ],
        ]);
    }
}
