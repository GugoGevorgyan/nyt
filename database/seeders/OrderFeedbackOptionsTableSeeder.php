<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class OrderFeedbackOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_feedback_options')->delete();

        DB::table('order_feedback_options')->insert(array(
            0 =>
                array(
                    'assessment' => '',
                    'canceled' => 1,
                    'completed' => 0,
                    'name' => 'Другое такси по пути',
                    'option' => 1,
                    'order_feedback_option_id' => 1,
                    'owner_type' => 'client',
                ),
            1 =>
                array(
                    'assessment' => '',
                    'canceled' => 1,
                    'completed' => 0,
                    'name' => 'Заказ был долгим ',
                    'option' => 2,
                    'order_feedback_option_id' => 2,
                    'owner_type' => 'client',
                ),
            2 =>
                array(
                    'assessment' => '',
                    'canceled' => 1,
                    'completed' => 0,
                    'name' => 'Я передумал ',
                    'option' => 3,
                    'order_feedback_option_id' => 3,
                    'owner_type' => 'client',
                ),
            3 =>
                array(
                    'assessment' => '',
                    'canceled' => 1,
                    'completed' => 0,
                    'name' => 'Случайный Заказ ',
                    'option' => 4,
                    'order_feedback_option_id' => 4,
                    'owner_type' => 'client',
                ),
            4 =>
                array(
                    'assessment' => '4,5',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Хороший собеседник',
                    'option' => 5,
                    'order_feedback_option_id' => 5,
                    'owner_type' => 'client',
                ),
            5 =>
                array(
                    'assessment' => '4,5',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Комфортная езда',
                    'option' => 6,
                    'order_feedback_option_id' => 6,
                    'owner_type' => 'client',
                ),
            6 =>
                array(
                    'assessment' => '4,5',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Хорошая музыка',
                    'option' => 7,
                    'order_feedback_option_id' => 7,
                    'owner_type' => 'client',
                ),
            7 =>
                array(
                    'assessment' => '3',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Грязная машина ',
                    'option' => 8,
                    'order_feedback_option_id' => 8,
                    'owner_type' => 'client',
                ),
            8 =>
                array(
                    'assessment' => '3',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Грубый водитель ',
                    'option' => 9,
                    'order_feedback_option_id' => 9,
                    'owner_type' => 'client',
                ),
            9 =>
                array(
                    'assessment' => '3',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Машина плохом состояни',
                    'option' => 10,
                    'order_feedback_option_id' => 10,
                    'owner_type' => 'client',
                ),
            10 =>
                array(
                    'assessment' => '1,2',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Не имел сдачи',
                    'option' => 11,
                    'order_feedback_option_id' => 13,
                    'owner_type' => 'client',
                ),
            11 =>
                array(
                    'assessment' => '1,2',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Слишком быстро ехал',
                    'option' => 12,
                    'order_feedback_option_id' => 14,
                    'owner_type' => 'client',
                ),
            12 =>
                array(
                    'assessment' => '1,2',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Аварийни ситуации ',
                    'option' => 13,
                    'order_feedback_option_id' => 15,
                    'owner_type' => 'client',
                ),
            13 =>
                array(
                    'assessment' => '1,2',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Курил в салоне ',
                    'option' => 14,
                    'order_feedback_option_id' => 16,
                    'owner_type' => 'driver',
                ),
            14 =>
                array(
                    'assessment' => '1,2',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Не имел деньги',
                    'option' => 15,
                    'order_feedback_option_id' => 17,
                    'owner_type' => 'driver',
                ),
            15 =>
                array(
                    'assessment' => '1,2',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Слишком много болтал ',
                    'option' => 16,
                    'order_feedback_option_id' => 18,
                    'owner_type' => 'driver',
                ),
            16 =>
                array(
                    'assessment' => '2,3',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Нормальный пассажир',
                    'option' => 17,
                    'order_feedback_option_id' => 19,
                    'owner_type' => 'driver',
                ),
            17 =>
                array(
                    'assessment' => '2,3',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Ненормальный пассажир',
                    'option' => 18,
                    'order_feedback_option_id' => 20,
                    'owner_type' => 'driver',
                ),
            18 =>
                array(
                    'assessment' => '2,3',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Колесо спустило ',
                    'option' => 19,
                    'order_feedback_option_id' => 21,
                    'owner_type' => 'driver',
                ),
            19 =>
                array(
                    'assessment' => '3,4',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Плохо чувствую',
                    'option' => 20,
                    'order_feedback_option_id' => 22,
                    'owner_type' => 'driver',
                ),
            20 =>
                array(
                    'assessment' => '3,4',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Машина заглохла ',
                    'option' => 21,
                    'order_feedback_option_id' => 23,
                    'owner_type' => 'driver',
                ),
            21 =>
                array(
                    'assessment' => '3,4',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Вро ди бы неплохо',
                    'option' => 22,
                    'order_feedback_option_id' => 24,
                    'owner_type' => 'driver',
                ),
            22 =>
                array(
                    'assessment' => '4,5',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Настоящий ниндзя ',
                    'option' => 23,
                    'order_feedback_option_id' => 25,
                    'owner_type' => 'driver',
                ),
            23 =>
                array(
                    'assessment' => '4,5',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Сэми Насери',
                    'option' => 24,
                    'order_feedback_option_id' => 26,
                    'owner_type' => 'driver',
                ),
            24 =>
                array(
                    'assessment' => '4,5',
                    'canceled' => 0,
                    'completed' => 1,
                    'name' => 'Колин Макрэй',
                    'option' => 25,
                    'order_feedback_option_id' => 27,
                    'owner_type' => 'driver',
                ),
            25 =>
                array(
                    'assessment' => '0',
                    'canceled' => 1,
                    'completed' => 0,
                    'name' => 'Беспредел просто ',
                    'option' => 26,
                    'order_feedback_option_id' => 28,
                    'owner_type' => 'driver',
                ),
            26 =>
                array(
                    'assessment' => '0',
                    'canceled' => 1,
                    'completed' => 0,
                    'name' => 'Что за манеры ',
                    'option' => 27,
                    'order_feedback_option_id' => 29,
                    'owner_type' => 'driver',
                ),
            27 =>
                array(
                    'assessment' => '0',
                    'canceled' => 1,
                    'completed' => 0,
                    'name' => 'Все я  иду домой ',
                    'option' => 28,
                    'order_feedback_option_id' => 30,
                    'owner_type' => 'driver',
                ),
        ));
    }
}
