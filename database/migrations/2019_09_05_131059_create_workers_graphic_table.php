<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateWorkersGraphicTable
 */
class CreateWorkersGraphicTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers_graphic', function (Blueprint $table) {
            $table->increments('worker_graphic_id')->index('index_worker_graphic_id');
            $table->smallInteger('work_days_count');
            $table->enum('work_days', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->smallInteger('weekend_days_count');
            $table->enum('weekend_days',
                [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday'
                ])->comment('enum(\'Monday\', \'Tuesday\', \'Wednesday\', \'Thursday\', \'Friday\', \'Saturday\', \'Sunday\')');
            $table->text('opening_hours');
            $table->timestamps(6);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('workers_graphic');
    }

}
