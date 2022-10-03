<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            0 => 
            array (
                'menu_id' => 1,
                'route_id' => NULL,
                'parent_id' => NULL,
                'title' => 'Водительская',
                'description' => 'Водительская',
                'icon' => 'mdi-account-multiple-outline',
                'created_at' => '2021-07-29 12:23:12.462181',
                'updated_at' => '2021-07-29 12:23:12.462181',
            ),
            1 => 
            array (
                'menu_id' => 2,
                'route_id' => NULL,
                'parent_id' => NULL,
                'title' => 'Диспетчерская ',
                'description' => 'Распределение заказов ',
                'icon' => 'mdi-phone-log-outline',
                'created_at' => '2021-07-29 12:23:12.462181',
                'updated_at' => '2021-07-29 12:23:12.462181',
            ),
            2 => 
            array (
                'menu_id' => 3,
                'route_id' => 1,
                'parent_id' => NULL,
                'title' => 'Панель управления',
                'description' => 'Панель управления',
                'icon' => 'mdi-view-dashboard-outline',
                'created_at' => '2021-07-29 12:23:11.402557',
                'updated_at' => '2021-07-29 12:23:11.402557',
            ),
            3 => 
            array (
                'menu_id' => 4,
                'route_id' => 24,
                'parent_id' => NULL,
                'title' => 'Личный кабинет',
                'description' => 'Личный кабинет',
                'icon' => 'mdi-home-circle-outline',
                'created_at' => '2021-07-29 12:23:11.445564',
                'updated_at' => '2021-07-29 12:23:11.445564',
            ),
            4 => 
            array (
                'menu_id' => 5,
                'route_id' => 2,
                'parent_id' => 1,
                'title' => 'Кандидаты',
                'description' => 'Кандидаты в водители',
                'icon' => 'mdi-car-info',
                'created_at' => '2021-07-29 12:23:11.593864',
                'updated_at' => '2021-07-29 12:23:11.593864',
            ),
            5 => 
            array (
                'menu_id' => 6,
                'route_id' => NULL,
                'parent_id' => NULL,
                'title' => 'Парк',
                'description' => 'Парки',
                'icon' => 'mdi-car-multiple',
                'created_at' => '2021-07-29 12:23:11.661099',
                'updated_at' => '2021-07-29 12:23:11.661099',
            ),
            6 => 
            array (
                'menu_id' => 7,
                'route_id' => NULL,
                'parent_id' => NULL,
                'title' => 'Клиентская',
                'description' => 'Клиентская',
                'icon' => 'mdi-facebook-workplace',
                'created_at' => '2021-07-29 12:23:11.700695',
                'updated_at' => '2021-07-29 12:23:11.700695',
            ),
            7 => 
            array (
                'menu_id' => 8,
                'route_id' => 7,
                'parent_id' => NULL,
                'title' => 'Дорожная безопасность',
                'description' => 'Дорожная безопасность',
                'icon' => 'mdi-security',
                'created_at' => '2021-07-29 12:23:11.742016',
                'updated_at' => '2021-07-29 12:23:11.742016',
            ),
            8 => 
            array (
                'menu_id' => 9,
                'route_id' => 8,
                'parent_id' => 6,
                'title' => 'Админстратор',
                'description' => 'Админстратор парка',
                'icon' => 'mdi-parking',
                'created_at' => '2021-07-29 12:23:11.799439',
                'updated_at' => '2021-07-29 12:23:11.799439',
            ),
            9 => 
            array (
                'menu_id' => 10,
                'route_id' => 10,
                'parent_id' => 6,
                'title' => 'Путевые листы',
                'description' => 'Путевые листы',
                'icon' => 'mdi-road',
                'created_at' => '2021-07-29 12:23:11.845830',
                'updated_at' => '2021-07-29 12:23:11.845830',
            ),
            10 => 
            array (
                'menu_id' => 11,
                'route_id' => 11,
                'parent_id' => 1,
                'title' => 'Водители',
                'description' => 'Водители',
                'icon' => 'mdi-account-multiple-outline',
                'created_at' => '2021-07-29 12:23:11.921726',
                'updated_at' => '2021-07-29 12:23:11.921726',
            ),
            11 => 
            array (
                'menu_id' => 12,
                'route_id' => 12,
                'parent_id' => 7,
                'title' => 'Компании',
                'description' => 'Компании',
                'icon' => 'mdi-alpha-c-circle-outline',
                'created_at' => '2021-07-29 12:23:12.009774',
                'updated_at' => '2021-07-29 12:23:12.009774',
            ),
            12 => 
            array (
                'menu_id' => 13,
                'route_id' => 22,
                'parent_id' => 7,
                'title' => 'Юридические лица',
                'description' => 'Юридические лица',
                'icon' => 'mdi-gavel',
                'created_at' => '2021-07-29 12:23:12.057232',
                'updated_at' => '2021-07-29 12:23:12.057232',
            ),
            13 => 
            array (
                'menu_id' => 14,
                'route_id' => NULL,
                'parent_id' => NULL,
                'title' => 'Отзывы && Жалобы',
                'description' => 'Отзывы && Жалобы',
                'icon' => 'mdi-comment-quote-outline',
                'created_at' => '2021-07-29 12:23:12.123632',
                'updated_at' => '2021-07-29 12:23:12.123632',
            ),
            14 => 
            array (
                'menu_id' => 15,
                'route_id' => 15,
                'parent_id' => 1,
                'title' => 'Расписание',
                'description' => 'Расписание водителей',
                'icon' => 'mdi-calendar-arrow-right',
                'created_at' => '2021-07-29 12:23:12.179450',
                'updated_at' => '2021-07-29 12:23:12.179450',
            ),
            15 => 
            array (
                'menu_id' => 16,
                'route_id' => 16,
                'parent_id' => NULL,
                'title' => 'Типы водителей',
                'description' => 'Типы водителей',
                'icon' => 'mdi-format-list-bulleted-type',
                'created_at' => '2021-07-29 12:23:12.230405',
                'updated_at' => '2021-07-29 12:23:12.230405',
            ),
            16 => 
            array (
                'menu_id' => 17,
                'route_id' => 17,
                'parent_id' => 14,
                'title' => 'Жалобы',
                'description' => 'Жалобы на роботников',
                'icon' => 'mdi-email-send-outline',
                'created_at' => '2021-07-29 12:23:12.267408',
                'updated_at' => '2021-07-29 12:23:12.267408',
            ),
            17 => 
            array (
                'menu_id' => 18,
                'route_id' => 19,
                'parent_id' => 1,
                'title' => 'Контракты',
                'description' => 'Контракты водителей',
                'icon' => 'mdi-file-document-box-multiple-outline',
                'created_at' => '2021-07-29 12:23:12.323401',
                'updated_at' => '2021-07-29 12:23:12.323401',
            ),
            18 => 
            array (
                'menu_id' => 19,
                'route_id' => 20,
                'parent_id' => 2,
                'title' => 'Оператор',
                'description' => 'Оператор колл-центра',
                'icon' => 'mdi-phone-incoming-outline',
                'created_at' => '2021-07-29 12:23:12.390724',
                'updated_at' => '2021-07-29 12:23:12.390724',
            ),
            19 => 
            array (
                'menu_id' => 20,
                'route_id' => 21,
                'parent_id' => 2,
                'title' => 'Диспетчер',
                'description' => 'Диспетчер колл-центра',
                'icon' => 'mdi-phone-log-outline',
                'created_at' => '2021-07-29 12:23:12.426886',
                'updated_at' => '2021-07-29 12:23:12.426886',
            ),
            20 => 
            array (
                'menu_id' => 21,
                'route_id' => NULL,
                'parent_id' => NULL,
                'title' => 'Бухгалтерия',
                'description' => 'Бухгалтерия',
                'icon' => 'mdi-file-chart-outline',
                'created_at' => '2021-07-29 12:23:12.462181',
                'updated_at' => '2021-07-29 12:23:12.462181',
            ),
            21 => 
            array (
                'menu_id' => 22,
                'route_id' => 25,
                'parent_id' => 7,
                'title' => 'Клиенты',
                'description' => 'Клиенты ',
                'icon' => 'mdi-human-greeting',
                'created_at' => '2021-07-29 12:23:12.462181',
                'updated_at' => '2021-07-29 12:23:12.462181',
            ),
            22 => 
            array (
                'menu_id' => 23,
                'route_id' => 26,
                'parent_id' => NULL,
                'title' => 'Штрафы',
                'description' => 'Штрафы',
                'icon' => 'mdi-call-split',
                'created_at' => '2022-01-27 12:23:12.462181',
                'updated_at' => '2022-01-27 12:23:12.462181',
            ),
            23 => 
            array (
                'menu_id' => 24,
                'route_id' => 23,
                'parent_id' => 21,
                'title' => 'Все транзакции',
                'description' => 'Все транзакции',
                'icon' => 'mdi-file-chart-outline',
                'created_at' => '2022-01-27 12:23:12.462181',
                'updated_at' => '2022-01-27 12:23:12.462181',
            ),
            24 => 
            array (
                'menu_id' => 25,
                'route_id' => 27,
                'parent_id' => 21,
                'title' => 'Компании',
                'description' => 'Компании',
                'icon' => 'mdi-office-building',
                'created_at' => '2022-01-27 12:23:12.462181',
                'updated_at' => '2022-01-27 12:23:12.462181',
            ),
            25 => 
            array (
                'menu_id' => 26,
                'route_id' => 28,
                'parent_id' => 21,
                'title' => 'Водители',
                'description' => 'Водители',
                'icon' => 'mdi-account-multiple-outline',
                'created_at' => '2022-01-27 12:23:12.462181',
                'updated_at' => '2022-01-27 12:23:12.462181',
            ),
            26 => 
            array (
                'menu_id' => 27,
                'route_id' => 5,
                'parent_id' => 6,
                'title' => 'Парки',
                'description' => 'Парки',
                'icon' => 'mdi-car-multiple',
                'created_at' => '2021-07-29 12:23:11.661099',
                'updated_at' => '2021-07-29 12:23:11.661099',
            ),
            27 => 
            array (
                'menu_id' => 28,
                'route_id' => 6,
                'parent_id' => 7,
                'title' => 'Работники',
                'description' => 'Работники',
                'icon' => 'mdi-facebook-workplace',
                'created_at' => '2021-07-29 12:23:11.700695',
                'updated_at' => '2021-07-29 12:23:11.700695',
            ),
            28 => 
            array (
                'menu_id' => 29,
                'route_id' => 13,
                'parent_id' => 14,
                'title' => 'Отзывы',
                'description' => 'Отзывы',
                'icon' => 'mdi-comment-quote-outline',
                'created_at' => '2021-07-29 12:23:12.123632',
                'updated_at' => '2021-07-29 12:23:12.123632',
            ),
        ));
        
        
    }
}