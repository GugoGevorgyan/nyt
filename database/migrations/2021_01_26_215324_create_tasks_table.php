<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateTasksTable
 */
class CreateTasksTable extends Migration
{
    protected const CRON_TASKS = [
        'everyMinute',
        'everyTwoMinutes',
        'everyThreeMinutes',
        'everyFourMinutes',
        'everyFiveMinutes',
        'everyTenMinutes',
        'everyFifteenMinutes',
        'everyThirtyMinutes',
        'hourly',
        'hourlyAt',
        'everyTwoHours',
        'everyThreeHours',
        'everyFourHours',
        'everySixHours',
        'twiceDaily',
        'daily',
        'weekdays',
        'weekends',
        'mondays',
        'tuesdays',
        'wednesdays',
        'thursdays',
        'fridays',
        'saturdays',
        'sundays',
        'weekly',
        'monthly',
        'lastDayOfMonth',
        'yearly'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tasks',
            function (Blueprint $table) {
                $table->increments('task_id')->index('index_tasks_id');
                $table->string('command')->index('index_tasks_command');
                $table->set('every', self::CRON_TASKS);
                $table->json('params')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamp('created_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
