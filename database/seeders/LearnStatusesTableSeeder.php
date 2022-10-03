<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Driver\LearnStatus;

/**
 * Class LearnStatusesTableSeeder
 * @package Database\Seeders
 */
class LearnStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LearnStatus::create([
            'name' => 'В процессе',
            'value' => 'study',
        ]);

        LearnStatus::create([
            'name' => 'Ожидает',
            'value' => 'waiting'
        ]);

        LearnStatus::create([
            'name' => 'Завершено',
            'value' => 'finished'
        ]);

        LearnStatus::create([
            'name' => 'Уволен',
            'value' => 'fired'
        ]);
    }
}
