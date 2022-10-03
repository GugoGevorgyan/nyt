<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Models\Car\CarStatus;

class CarStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarStatus::create([
            'color' => '#00C853',
            'text' => 'Рабочий',
            'value' => 'working',
            'description' => 'В рабочем сосотоянии',
        ]);

        CarStatus::create([
            'color' => '#FB8C00',
            'text' => 'На ремонте',
            'value' => 'repairs',
            'description' => 'На ремонте',
        ]);

        CarStatus::create([
            'color' => '#E53935',
            'text' => 'Аварийный',
            'value' => 'emergency',
            'description' => 'Не в рабочем сосотоянии',
        ]);

        CarStatus::create([
            'color' => '#82B1FF',
            'text' => 'Не зарегистрирован',
            'value' => 'unregistered',
            'description' => 'Не зарегистрированный в ГИБДД',
        ]);

        CarStatus::create([
            'color' => '#B0BEC5',
            'text' => 'Удален',
            'value' => 'removed',
            'description' => 'Снят с баланса в учете',
        ]);
    }
}
