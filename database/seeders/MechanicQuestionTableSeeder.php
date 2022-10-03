<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

/**
 * Class MechanicQuestionTableSeeder
 */
class MechanicQuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('car_report_questions')->insert([
            0 =>
                [
                    'question' => 'messages.mechanic_question_emergency_lights',
                    'field_name' => 'emergency_lights',
                    'point' => 2
                ],
            1 =>
                [
                    'question' => 'messages.mechanic_question_headlights',
                    'field_name' => 'headlights',
                    'point' => 3
                ],
            2 =>
                [
                    'question' => 'messages.mechanic_question_tires',
                    'field_name' => 'tires',
                    'point' => 1
                ],
            3 =>
                [
                    'question' => 'messages.mechanic_question_engine',
                    'field_name' => 'engine',
                    'point' => 4
                ],
        ]);
    }
}
