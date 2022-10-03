<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Franchise\FranchisePhone;

class FranchisePhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FranchisePhone::create([
            'franchise_id' => 1,
            'number' => '600'
        ]);
    }
}
