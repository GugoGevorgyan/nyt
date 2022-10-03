<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class ModulesTableSeeder
 */
class ModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('modules')->insert(
            [
                0 =>
                    [
                        'name' => 'call_center',
                        'text' => 'колл-центр',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 1
                    ],
                1 =>
                    [
                        'name' => 'park',
                        'text' => 'парк',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 1
                    ],
                2 =>
                    [
                        'name' => 'aggregator_drivers',
                        'text' => 'водители агрегаторы',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 1
                    ],
                3 =>
                    [
                        'name' => 'corporate_drivers',
                        'text' => 'корпоративные водители',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 0
                    ],

                4 =>
                    [
                        'name' => 'park_mechanic_app',
                        'text' => 'приложение механика',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 0
                    ],
                5 =>
                    [
                        'name' => 'traffic_safety_department',
                        'text' => 'отдел безопасности движения',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 0
                    ],
                6 =>
                    [
                        'name' => 'bookkeeping',
                        'text' => 'бухгалтерия',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 0
                    ],
                7 =>
                    [
                        'name' => 'sales_department_development_of_individual_tariffs',
                        'text' => 'отдел продаж, разработка индивидуальных тарифов',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 0
                    ],
                8 =>
                    [
                        'name' => 'personal_department',
                        'text' => 'отдел кадров',
                        'alias' => 'personal_department',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 0
                    ],
                9 =>
                    [
                        'name' => 'park_manager_app',
                        'text' => 'приложение начальника парка',
                        'alias' => 'park_manager_app',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur cum facilis iusto nobis odit! Beatae debitis, deserunt dolor dolore ducimus fuga harum perspiciatis possimus quibusdam, quod reprehenderit sint, tempora vero!',
                        'created_at' => null,
                        'updated_at' => null,
                        'default' => 0
                    ],
            ]
        );
    }
}
