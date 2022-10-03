<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_schedules', function (Blueprint $table) {
            $table->increments('driver_schedule_id')->index('index_driver_schedule_id');
            $table->integer('driver_id')->unsigned()->index('drivers_id');
            $table->integer('driver_contract_id')->unsigned()->index('driver_contracts_id');
            $table->boolean('working');
            $table->boolean('accident')->default(false);
            $table->date('date');
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->timestamps(6);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_schedules');
    }
}
