<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Franchise\FranchiseSubPhone;

/**
 * Class FranchiseSubPhonesTableSeeder
 */
class FranchiseSubPhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        FranchiseSubPhone::create(
            [
                'franchise_phone_id' => 1,
                'number' => '100',
                'password' => 'ef7f06312d50b'
            ]
        );

        FranchiseSubPhone::create(
            [
                'franchise_phone_id' => 1,
                'number' => '101',
                'password' => 'e0aefde849555e'
            ]
        );
    }
}
